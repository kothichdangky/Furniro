<script setup>
import { useCartStore } from '@/composables/useCartStore'
import api from '@/api'
const cart = useCartStore()
const items = computed(() => cart.getProductsInCart)

const fields = [
  { key: 'image', label: '' },
  { key: 'title', label: 'Product' },
  { key: 'size', label: 'Size' },
  { key: 'price', label: 'Price' },
  { key: 'quantity', label: 'Quantity', class: 'text-center' },
  { key: 'total', label: 'Total' },
  { key: 'actions', label: '' },
]

const onQuantityChange = (index, value) => {
const quantity = Number(value)

  // Nếu không nhập gì hoặc nhập 0 → xoá luôn
  if (isNaN(quantity) || quantity < 1) {
    cart.removeAllProduct(index)
    return
  }

  // Cập nhật số lượng
  const product = cart.cartProducts[index]

  if (quantity > product.quantity) {
    alert(`Chỉ còn ${product.quantity} sản phẩm trong kho`)
    product.cartQuantity = product.quantity // ✅ cập nhật lại giá trị input
    cart.syncCartToServer()
    return
  }

  // Nếu vượt tồn kho → chặn
  if (quantity > product.quantity) {
    alert('Vượt quá số lượng tồn kho')
    return
  }

  // ✅ cập nhật đúng field cartQuantity
  product.cartQuantity = quantity
  cart.syncCartToServer() // Gửi lên backend nếu cần
}

const onlyAllowNumbers = (event) => {
  const allowedKeys = [
    'Backspace',
    'Delete',
    'ArrowLeft',
    'ArrowRight',
    'Tab',
    'Home',
    'End'
  ]

  // Cho phép các phím điều hướng
  if (allowedKeys.includes(event.key)) return

  // Chặn nếu không phải là số
  if (!/^[0-9]$/.test(event.key)) {
    event.preventDefault()
  }
}

const checkoutWithPaypal = async () => {
  try {
    if (!cart.cartProducts || !Array.isArray(cart.cartProducts)) {
      alert('Giỏ hàng trống hoặc không hợp lệ.')
      return
    }

    const total = cart.cartProducts.reduce((sum, item) => {
      const price = item.price_sale && item.price_sale > 0 ? item.price_sale : item.price
      return sum + price * item.cartQuantity
    }, 0).toFixed(2)

    if (total <= 0) {
      alert('Tổng thanh toán không hợp lệ.')
      return
    }

       window.location.href = `http://localhost:8000/paypal?total=${total}`
    console.log('Đang chuyển hướng đến PayPal...')
  } catch (err) {
     console.error('❌ Chi tiết lỗi thanh toán:', err.response?.data || err.message || err)
    alert('Không thể thực hiện thanh toán.')
  }
}

onMounted(async () => {
  await cart.fetchCartFromServer()
  console.log('Cart data:', cart.getProductsInCart)
})
</script>

<template>
  <div>
    <section class="poster d-flex mb-5">
      <BContainer class="text-center py-5 my-5">
        <h1>Shop</h1>
        <div class="mt-3"><span class="fw-bold">Home</span> <span>></span> <span>Shop</span></div>
      </BContainer>
    </section>
    <BContainer class="pb-5 mb-5">
      <BRow class="justify-content-between">
        <BCol cols="12" lg="8" md="8" sm="8" class="">
          <div v-if="cart.hasProduct">
            <BTable
              :items="cart.getCartItemsWithTotal"
              :fields="fields"
              thead-class="bg-warning-7"
              table-class="my-table table-borderless align-middle "
            >
              <!-- Tuỳ biến cột ảnh -->
              <template #cell(image)="data">
                <img :src="data.item.image" width="100" height="100" />
              </template>

              <template #cell(title)="data">
                {{ data.item.product }}
              </template>

              <!-- Tuỳ biến cột giá -->
              <template #cell(price)="data">
                {{ (data.item.price_sale > 0 ? data.item.price_sale : data.item.price).toLocaleString() }}đ
              </template>

              <template #cell(quantity)="data">
                <div class="text-center">
                  <BFormInput
                     class="mx-auto d-block"
                    v-model="cart.cartProducts[data.index].cartQuantity"
                    min="1"
                    @keydown="onlyAllowNumbers"
                    @blur="(event) => onQuantityChange(data.index,  event.target.value)"
                    style="width: 43px; height: 42px; text-align: center"
                    required
                  />
                </div>
              </template>

              <!-- Cột hành động -->
              <template #cell(actions)="data">
                <a @click="cart.removeAllProduct(data.index)">
                  <img src="/src/images/cart/trash.png" alt=""
                /></a>
              </template>
            </BTable>
          </div>
          <div v-else>Giỏ hàng trống.</div>
        </BCol>
        <BCol cols="12" lg="4" md="4" sm="4" class="text-center">
          <div class="bg-warning-7">
            <h2>Cart Totals</h2>
            <div class="px-5 mx-5">
              <div class="d-flex justify-content pt-5">
                <b>Subtotal:</b>
                <p class="ps-5 text-secondary">Rs{{ cart.totalPrice.toLocaleString() }}</p>
              </div>
              <div class="d-flex align-items-center py-3 pb-5">
                <b>Total:</b>
                <span class="ps-5" style="font-size: 20px; color: #b88e2f">
                  Rs{{ cart.totalPrice.toLocaleString() }}
                </span>
              </div>
              <BButton
                to="/cart"
                @click="checkoutWithPaypal"
                class="border-3 rounded-4 bg-warning-7 text-dark py-3 px-5 mb-5 align-items-center"
                ><p class="m-0" style="font-size: 20px">CheckOut</p></BButton
              >
            </div>
          </div>
        </BCol>
      </BRow>
    </BContainer>
  </div>
</template>
<style >
.bg-warning-7 {
  background-color: #f9f1e7 !important;
}

.bg-warning-7 > tr > th {
  background-color: #f9f1e7 !important;
  padding: 24px 0;
  font-weight: bold;
}
.my-table th,
.my-table td {
  padding: 24px 0;
}

.poster {
  background-image: url('/src/images/shop/poster.png');
  background-size: cover;
  background-position: center;
  width: 100%;
}
</style>
