<script setup>
import { ref } from 'vue'
import { auth, googleProvider, facebookProvider } from '@/composables/Firebase'
import { signInWithPopup } from 'firebase/auth'
import { onClickOutside } from '@vueuse/core'
import api from '@/api'

const email = ref('')
const password = ref('')
const errorMessage = ref('')

const emit = defineEmits(['close'])

const loginWithProvider = async (provider) => {
  try {
    const selectedProvider = provider === 'google' ? googleProvider : facebookProvider

    // Bắt buộc: lấy CSRF cookie từ Laravel Sanctum
    await api.get('/sanctum/csrf-cookie')

    // Đăng nhập Firebase
    const result = await signInWithPopup(auth, selectedProvider)
    const idToken = await result.user.getIdToken()

    // Gửi token về Laravel để xác thực
    const res = await api.post('/api/firebase-auth', { token: idToken })

    // Lấy user info (nếu cần)
    const me = await api.get('/api/user')
    console.log(me.data)

    emit('login-success', res.data)
    emit('close')
  } catch (err) {
    errorMessage.value = err.response?.data?.message || 'Lỗi đăng nhập Firebase'
    console.error(err)
  }
}

const handleLogin = async () => {
  try {
    errorMessage.value = ''
    await api.get('http://localhost:8000/sanctum/csrf-cookie') // Bắt buộc để tạo cookie CSRF
    const res = await api.post('/api/login', {
      email: email.value,
      password: password.value
    })
    console.log('Đăng nhập thành công:', res.data)
    const me = await api.get('/api/user')
    console.log(me.data)
    emit('login-success', res.data)
    emit('close') // Đóng popup nếu login thành công
  } catch (err) {
    errorMessage.value = err.response?.data?.message || 'Lỗi đăng nhập'
  }
}

const popupRef = ref(null)

onClickOutside(popupRef, () => {
  emit('close')
})

const openRegister = () => {
  emit('close')             // Đóng popup hiện tại
  emit('open-register')     // Thông báo mở popup đăng kí
}
</script>


<template>
  <div class="popup-signin-wrapper">
    <Mask @click="emit('close')" />
    <div ref="popupRef" class="popup-signin bg-white rounded shadow p-4">
      <h3 class="mb-3 text-center">Đăng nhập</h3>
      <p v-if="errorMessage" class="text-danger text-center">{{ errorMessage }}</p>


      <BForm @submit.prevent="handleLogin">

        <BFormGroup label="Email" label-for="email" class="mb-3">
          <BFormInput
            id="email"
            type="email"
            required
            class="form-control"
            v-model="email"
          />
        </BFormGroup>

        <BFormGroup label="Mật khẩu" label-for="password" class="mb-3">
          <BFormInput
            id="password"
            type="password"
            required
            class="form-control"
            v-model="password"
          />
        </BFormGroup>

        <div class="d-flex justify-content-end gap-2">
          <BButton type="submit" class="btn-warning-2 w-100 w-25 text-light">Đăng nhập</BButton>
          <BButton type="submit" @click.prevent="openRegister" class="btn-warning-3 w-100 text-dark">Đăng kí</BButton>
        </div>
      </BForm>

      <div class="divider my-3 text-center text-muted">Hoặc</div>

      <BRow class="justify-content-center">
        <BCol lg="6">
          <BButton
            variant="outline-primary"
            class="w-100 d-flex align-items-center justify-content-center "
            @click="loginWithProvider('facebook')"
          >
            <i class="fab fa-facebook-f"></i> Facebook
          </BButton>
        </BCol>
        <BCol lg="6">
          <BButton
            variant="outline-danger"
            class="w-100 d-flex align-items-center justify-content-center "
            @click="loginWithProvider('google')"
          >
            <i class="fab fa-google"></i> Google
          </BButton>
        </BCol>
        <BCol>
        </BCol>
      </BRow>
    </div>
  </div>
</template>

<style scoped>
.btn-warning-2 {
  background-color: #b88e2f;
}
.btn-warning-3 {
  background-color: #fff3e3;
}

.popup-signin-wrapper {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  z-index: 1000;
}

.popup-signin {
  position: fixed;
  top: 50%;
  left: 50%;
  width: 400px;
  transform: translate(-50%, -50%);
  z-index: 1001;
}

.divider {
  border-top: 1px solid #ddd;
}

</style>
