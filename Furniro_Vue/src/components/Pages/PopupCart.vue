<script setup>
import { onClickOutside } from '@vueuse/core'
import { useCartStore } from '@/composables/useCartStore'
import {  useRouter } from 'vue-router/auto'
import api from '@/api'

const router = useRouter()
const cart = useCartStore()
const popupRef = ref(null)
onClickOutside(popupRef, () => {
  if (cart.showPopupCart) cart.togglePopupCart()
})

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

onMounted(() => {
  const successMessage = new URLSearchParams(window.location.search).get('success')
  if (successMessage) {
    cart.clearCart()
  }
})

</script>

<template>
  <div ref="popupRef" v-if="cart.showPopupCart" class="">
    <Mask @click="cart.togglePopupCart" />
    <div class="popup-cart bg-white rounded shadow">
      <div v-if="cart.hasProduct">
        <BRow class="justify-content-between align-items-center">
          <BCol cols="12" lg="10">
            <h3 class="py-3 border-bottom">Shopping Cart</h3>
          </BCol>
          <BCol cols="12" lg="2">
            <a @click="cart.togglePopupCart"><img src="/src/images/cart/Group.png" alt="" /></a>
          </BCol>
        </BRow>
        <div
          v-for="(product, index) in cart.getProductsInCart"
          :key="index"
          class="d-flex align-items-center justify-content-between mb-3 pb-2"
        >

            <img :src="product.image" class="w-25 h-25" style="object-fit: cover" />

          <div class="flex-grow-1 ms-3">
            <span class="mb-0 fw-semibold"
            @click="router.push(`/product_detail/${product.id}`)"
            style="cursor: pointer" > {{ product.product }}
            {{ product.size }}

            </span>
            <p class="mb-0 text-muted">
              {{ product.cartQuantity }} x
              <span class="text-warning-2"
                >{{
                  (product.price_sale > 0 ? product.price_sale : product.price).toLocaleString()
                }}đ</span
              >
            </p>
          </div>
          <!-- <button class="btn btn-secondary rounded-circle me-3" @click="cart.removeProduct(index)">
            X
          </button> -->
          <BButton
            @click="cart.removeAllProduct(index)"
            class="btn btn-secondary rounded-circle me-3"
            >X</BButton
          >
        </div>
        <BRow class="justify-content-between align-items-center">
          <BCol cols="12" lg="3">
            <div class="text-start fw-bold">Subtotal</div>
          </BCol>
          <BCol cols="12" lg="8">
            <div class="text-start fw-bold text-warning-2">
              {{ cart.totalPrice.toLocaleString() }}đ
            </div>
          </BCol>
        </BRow>
        <div class="mt-auto border-top">
          <div class="py-4">
            <BButton
              to="/cart"
              @click="cart.togglePopupCart"
              class="border-3 rounded-5 bg-light text-dark ps-4 pe-4 me-3 align-items-center"
              ><p class="m-0">cart</p></BButton
            >
            <BButton
              @click="checkoutWithPaypal"
              class="border-3 rounded-5 bg-light text-dark ps-4 pe-4 me-3 align-items-center"
              ><p class="m-0">checkout</p></BButton
            >
            <BButton
              to="/"
              class="border-3 rounded-5 bg-light text-dark ps-4 pe-4 me-3 align-items-center"
              ><p class="m-0">comparison</p></BButton
            >
          </div>
        </div>
      </div>

      <p v-else class="text-center">Giỏ hàng trống.</p>
    </div>
  </div>
</template>

<style scoped>
.popup-cart {
  position: fixed;
  top: 0px;
  right: 0px;
  width: 419px;

  padding: 16px;
  z-index: 1000;
}

p {
  font-size: 16px;
}

.text-warning-2 {
  color: #b88e2f;
}
</style>
