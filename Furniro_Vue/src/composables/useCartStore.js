import api from '@/api'

export const useCartStore = defineStore('cart', {
  state: () => ({
    cartProducts: [],
    showPopupCart: false,
  }),
  getters: {
    getProductsInCart: (state) => state.cartProducts,
    getCartCount: (state) => {
      return state.cartProducts.reduce((total, p) => total + Number(p.cartQuantity), 0)
    },
    totalPrice: (state) =>
      state.cartProducts.reduce((sum, p) => {
        const price = p.price_sale && p.price_sale > 0 ? p.price_sale : p.price
        return sum + price * p.cartQuantity
      }, 0),
    getCartItemsWithTotal: (state) => {
      return state.cartProducts.map((product) => {
        const priceToUse =
          product.price_sale && product.price_sale > 0 ? product.price_sale : product.price
        return {
          ...product,
          total: priceToUse * product.cartQuantity,
          finalPrice: priceToUse,
        }
      })
    },
    hasProduct: (state) => state.cartProducts.length > 0,
  },

  actions: {
    async fetchCartFromServer() {
      try {
        const res = await api.get('/api/cart')
        this.cartProducts = res.data
      } catch (err) {
        console.error('Không lấy đc cart:', err)
      }
    },

    async syncCartToServer() {
      try {
        await api.post('/api/cart', this.cartProducts)
      } catch (err) {
        console.error('không gửi được cart:', err)
      }
    },

    async clearCartFromServer() {
      try {
        await api.delete('/api/cart', this.cartProducts)
        this.cartProducts = []
      } catch (err) {
        console.error('không gửi được cart:', err)
      }
    },

    addProduct(product) {
      if (!product || typeof product !== 'object' || !product.id) return

      if (!product.size) {
        alert('⚠ Thiếu size khi thêm sản phẩm vào giỏ hàng!')
        return
      }

      const existing = this.cartProducts.find((p) => p.id === product.id && p.size === product.size)

      if (existing) {
        const newQty = existing.cartQuantity + (product.cartQuantity || 1)
        if (newQty > product.quantity) {
          alert('⚠️ Vượt quá số lượng còn hàng')
          return
        }
        existing.cartQuantity = newQty
      } else {
        this.cartProducts.push({
          id: product.id,
          product: product.product,
          image: product.image,
          price: product.price,
          price_sale: product.price_sale,
          quantity: product.quantity,
          size: product.size, // ✅ CHẮC CHẮN có dòng này
          cartQuantity: product.cartQuantity || 1,
        })
      }

      this.syncCartToServer()
    },
    reduceProduct(index) {
      const item = this.cartProducts[index]
      if (item.cartQuantity > 1) {
        item.cartQuantity -= 1
      } else {
        this.cartProducts.splice(index, 1)
      }
      this.syncCartToServer()
    },

    removeAllProduct(index) {
      this.cartProducts.splice(index, 1)
      this.syncCartToServer()
    },

    clearCart() {
      this.cartProducts = []
      this.clearCartFromServer()
    },

    togglePopupCart() {
      this.showPopupCart = !this.showPopupCart
    },
  },
})
