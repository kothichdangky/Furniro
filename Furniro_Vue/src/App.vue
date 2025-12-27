<script setup>
import { ref, computed, onMounted, nextTick } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import { useCartStore } from '@/composables/useCartStore'
import { useUserStore } from '@/stores/useUserStore'
import api from '@/api'

const router = useRouter()
const route = useRoute()

const user = ref(null)
const cart = useCartStore()
const cartCount = computed(() => cart.getCartCount)
const showPopupSignin = ref(false)
const showPopupRegister = ref(false)
const userStore = useUserStore()

// 🔍 Search State
const showSearchInput = ref(false)
const searchKeyword = ref('')
const inputRef = ref(null)

const toggleSearch = () => {
  if (showSearchInput.value && searchKeyword.value.trim()) {
    handleSearch()
  } else {
    showSearchInput.value = !showSearchInput.value
    if (showSearchInput.value) {
      nextTick(() => inputRef.value?.focus())
    }
  }
}

const handleSearch = () => {
  const trimmed = searchKeyword.value.trim()
  if (trimmed) {
    router.push({ path: '/shop', query: { q: trimmed } })
  } else {
    router.push({ path: '/shop' }) // 👉 load all
  }
}

onMounted(async () => {
  const token = route.query.token

  if (token) {
    localStorage.setItem('token', token)
    api.defaults.headers.common['Authorization'] = `Bearer ${token}`
  }

  userStore.initUserFromCache()
  await userStore.fetchUser()

  if (userStore.user) {
    await cart.fetchCartFromServer()
  } else {
    cart.loadCartFromStorage()
  }
})

const switchToRegister = () => {
  showPopupSignin.value = false
  showPopupRegister.value = true
}

const onLoginSuccess = async () => {
  await userStore.fetchUser()
  showPopupSignin.value = false
}

const logout = async () => {
  try {
    await api.post('/api/logout')
    userStore.clearUser()
    console.log('Đã đăng xuất')
  } catch (err) {
    console.error('Lỗi logout:', err)
  }
}
</script>
<style lang="css">
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.3s ease;
}
.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}

.navbar2 {
  width: 100%;
  position: sticky !important;
  top: 0;
  z-index: 1000;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.5);
}
.px-132 {
  padding-left: 280px !important;
}
.ps-264 {
  padding-left: 264px !important;
}
.pse-4 {
  padding: 0 24px !important;
}

.navbar-light .navbar-toggler-icon {
  background-image: url("data:image/svg+xml;charset=utf8,%3Csvg viewBox='0 0 30 30' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath stroke='black' stroke-width='2' stroke-linecap='round' stroke-miterlimit='10' d='M4 7h22M4 15h22M4 23h22'/%3E%3C/svg%3E");
}

.cart-icon {
  position: relative;
  display: inline-block;
}

.cart-count {
  position: absolute;
  top: -10px;
  right: -10px;
  background-color: red;
  color: white;
  border-radius: 50%;
  padding: 2px 6px;
  font-size: 12px;
  font-weight: bold;
  text-align: center;
}
@media (min-width: 377px) and (max-width: 992px) {
  .px-132 {
    padding-left: 0 !important;
  }
  .mobileflex {
    display: flex !important;
    flex-direction: row !important;
    flex-wrap: wrap;
    justify-content: end;
  }
  .ps-264 {
    padding: 0px !important;
  }

  .ps-132 {
    padding: 0px !important;
  }
}

.dropdown-item:active {
  background-color: #b88e2f !important; /* Màu vàng nhấn */
}
</style>
<template>
  <BNavbar
    v-b-color-mode="'dark'"
    toggleable="lg"
    variant="light"
    class="navbar2 ps-5 pe-5 pt-4 pb-4 navbar-light shadow"
  >
    <BNavbarBrand to="/" class=""
      ><img src="./images/Meubel House_Logos-05.png" alt="" /><img
        src="./images/SkinClinic.png"
        class="ps-2"
        alt=""
    /></BNavbarBrand>
    <BNavbarToggle target="nav-collapse" />
    <BCollapse id="nav-collapse" class="text-dark" is-nav>
      <BNavbarNav class="ps-264">
        <BNavItem class="pse-4" as="RouterLink" to="/"><b class="text-black">Home</b> </BNavItem>
        <BNavItem class="pse-4" as="RouterLink" to="/shop"><b class="text-black">Shop</b></BNavItem>
        <BNavItem disabled class="pse-4" as="RouterLink" to="/about"
          ><b class="text-secondary">About</b></BNavItem
        >
        <BNavItem disabled class="pse-4 text-secondary" as="RouterLink" to="/contact"
          ><b class="text-secondary">Contact</b></BNavItem
        >
      </BNavbarNav>
    </BCollapse>
    <BContainer>
      <BNavbarNav class="px-5 mobileflex">
        <!-- <BNavItem  @click="logout" style="width: auto">
          <b class="text-dark">  </b>
        </BNavItem> -->
        <BNavItemDropdown
          v-if="userStore.user"
          :text="userStore.user.name"
          toggle-class="nav-link text-dark fw-bold"
          right
        >
          <BDropdownItem @click="logout" href="#">Logout</BDropdownItem>
          <BDropdownItem
            class=""
            href="#"
            v-if="userStore.isFetched && userStore.user?.role === 'admin'"
            as="RouterLink"
            to="/admin/adminshop"
            >Admin</BDropdownItem
          >
        </BNavItemDropdown>

        <BNavItem v-else class="" href="#" @click.prevent="showPopupSignin = true">
          <img src="./images/Vector.png" alt="" />
        </BNavItem>

        <BNavItem
          class="ps-3 d-flex align-items-center"
          @click="toggleSearch"
          style="cursor: pointer"
        >
          <img src="./images/Vector2.png" alt="Search Icon" />
          <transition name="fade">
            <input
              v-if="showSearchInput"
              ref="inputRef"
              v-model="searchKeyword"
              @keyup.enter="handleSearch"
              type="text"
              placeholder="Tìm kiếm..."
              class="form-control me-2"
              style="width: 180px; transition: all 0.3s ease"
            />
          </transition>
        </BNavItem>

        <BNavItem class="ps-3" href="#"> <img src="./images/Vector3.png" alt="" /></BNavItem>
        <!-- @click.prevent ngăn cản việc trang wed tự di chuyển lên trên khi bấm vào -->
        <BNavItem class="ps-3 cart-icon" @click.prevent="cart.togglePopupCart">
          <img src="./images/Vector4.png" alt="" />
          <span v-if="cartCount > 0" class="cart-count">{{ cartCount }}</span>
        </BNavItem>
      </BNavbarNav>
    </BContainer>
  </BNavbar>

  <!-- <RouterView /> -->
  <RouterView v-slot="{ Component }" :key="$route.params.id">
    <keep-alive :include="['Shop']">
      <component :is="Component" />
    </keep-alive>
  </RouterView>
  <PopupSignin
    v-if="showPopupSignin"
    @login-success="onLoginSuccess"
    @close="showPopupSignin = false"
    @open-register="switchToRegister"
  />
  <PopupRegister
    v-if="showPopupRegister"
    @register-success="userStore.setUser($event)"
    @close="showPopupRegister = false"
  />
  <PopupCart />
  <Footer />
</template>
