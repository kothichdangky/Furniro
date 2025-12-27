<script lang="ts" setup>
import { onClickOutside } from '@vueuse/core'
import api from '@/api'

const name = ref('')
const email = ref ('')
const password = ref ('')
const password_confirmation = ref ('')
const errorMessage = ref ('')


const emit = defineEmits(['close', 'register-success'])
const popupRef = ref(null)

onClickOutside(popupRef, () => {
  emit('close')
})

const handleRegister = async () => {
  try {
    console.log('Đã nhấn đăng ký');
    errorMessage.value = ''
    await api.get('/sanctum/csrf-cookie') // Bắt buộc để tạo cookie CSRF
    await api.post('/api/register', {
      name: name.value,
      email: email.value,
      password: password.value,
      password_confirmation: password_confirmation.value
    });
    // Gọi lại user đúng chuẩn từ session
    const userRes = await api.get('/api/user');
    console.log('Đăng nhập thành công:', userRes.data)
    emit('register-success', userRes.data)
    emit('close') // Đóng popup nếu login thành công
    // Có thể emit thêm 'login-success' để gọi từ cha
  } catch (err) {
    console.log('Lỗi chi tiết:', err.response?.data);
    errorMessage.value = err.response?.data?.message || 'Lỗi đăng ký'
  }
}

const registerWithProvider = (provider: string) => {
  console.log(`Đăng ký với ${provider}`)
}
</script>

<template>
  <div class="popup-signin-wrapper">
    <Mask @click="emit('close')" />
    <div ref="popupRef" class="popup-signin bg-white rounded shadow p-4">
      <h3 class="mb-3 text-center">Đăng kí</h3>

      <BForm @submit.prevent="handleRegister">
        <BFormGroup label="Họ và tên" label-for="fullname" class="mb-3">
          <BFormInput
            id="name"
            type="text"
            required
            class="form-control"
            v-model="name"
          />
        </BFormGroup>

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
            autocomplete="new-password"
            v-model="password"
          />
        </BFormGroup>

        <BFormGroup label="Xác nhận mật khẩu" label-for="confirmPassword" class="mb-3">
          <BFormInput
            id="password_confirmation"
            type="password"
            required
            class="form-control"
            autocomplete="new-password"
            v-model="password_confirmation"
          />
        </BFormGroup>

        <div class="d-flex justify-content-end">
          <BButton type="submit" class="btn-warning-2 w-100 text-light">Đăng kí</BButton>
        </div>
      </BForm>

      <div class="divider my-3 text-center text-muted">Hoặc</div>

      <BRow class="gap-2 justify-content-between">
        <BCol>
          <BButton
            variant="outline-primary"
            class="w-100 d-flex align-items-center justify-content-center gap-2"
            @click="registerWithProvider('facebook')"
          >
            <i class="fab fa-facebook-f"></i> Facebook
          </BButton>
        </BCol>
        <BCol>
          <BButton
            variant="outline-danger"
            class="w-100 d-flex align-items-center justify-content-center gap-2"
            @click="registerWithProvider('google')"
          >
            <i class="fab fa-google"></i> Google
          </BButton>
        </BCol>
        <BCol>
          <BButton
            variant="outline-dark"
            class="w-100 d-flex align-items-center justify-content-center gap-2"
            @click="registerWithProvider('github')"
          >
            <i class="fab fa-github"></i> GitHub
          </BButton>
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
