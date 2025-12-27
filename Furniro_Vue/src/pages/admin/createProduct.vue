<script setup>

import api from '@/api'

const image = ref(null) // File chính
const addimage = ref([]) // Danh sách file thêm (gallery)
const imageInputs = ref([]) // Mảng quản lý input phụ

const product = ref('')
const intro = ref('')
const price = ref('')
const price_sale = ref('')
const quantity = ref('')
const badge = ref('')

// Thêm input ảnh mới (tối đa 5)
const addInput = () => {
  if (imageInputs.value.length >= 5) return
  imageInputs.value.push(Date.now()) // Dùng timestamp để đảm bảo key duy nhất
  addimage.value.push(undefined)
}

// Xử lý tạo sản phẩm
const handleProduct = async () => {
  try {
    console.log('Đã nhấn')

    const formData = new FormData()
    formData.append('product', product.value)
    formData.append('intro', intro.value)
    formData.append('price', price.value)
    formData.append('price_sale', price_sale.value)
    formData.append('quantity', quantity.value)
    formData.append('badge', badge.value)

    if (image.value) {
      formData.append('image', image.value)
    }

    addimage.value.forEach((file) => {
      if (file) {
        formData.append('addimage[]', file)
      }
    })

    const response = await api.post('/api/shop', formData, {
      headers: {
        'Content-Type': 'multipart/form-data',
      },
    })

    console.log('Tạo sản phẩm thành công', response.data)
    alert('Tạo sản phẩm thành công')

    // Reset form
    product.value = ''
    intro.value = ''
    price.value = ''
    price_sale.value = ''
    price_sale.value = ''
    quantity.value = ''
    image.value = null
    addimage.value = []
    imageInputs.value = []

  } catch (error) {
    console.log('Lỗi chi tiết:', error.response?.data)
  }
}
</script>


<template>
  <BContainer>
    <BForm @submit.prevent="handleProduct">
      <BFormFloatingLabel label="Product" class="my-2">
        <BFormInput v-model="product" id="Product" placeholder="Email address" />
      </BFormFloatingLabel>
      <BFormFile v-model="image" label="Chọn ảnh sản phẩm" :state="!!image" accept="image/*" />
      <BButton class="btn-warning mt-3  w-25 text-dark border" @click="addInput">thêm ảnh phụ</BButton><br>
      <div v-for="(key, index) in imageInputs" :key="key" class="mt-2">
      <BFormFile v-model="addimage[index]"
       :label="`Chọn ảnh phụ ${index + 1}`" :state="!!addimage[index]"  accept="image/*" />
      </div>
      <BFormFloatingLabel label="Intro" class="my-2">
        <BFormTextarea v-model="intro" id="Intro" placeholder="Intro" />
      </BFormFloatingLabel>
      <BFormFloatingLabel label="Price" class="my-2">
        <BFormInput v-model="price" id="Price" placeholder="Price" />
      </BFormFloatingLabel>
      <BFormFloatingLabel label="Price Sale" class="my-2">
        <BFormInput v-model="price_sale" id="Price Sale" placeholder="Price Sale" />
      </BFormFloatingLabel>
      <BFormFloatingLabel label="Quantity" class="my-2">
        <BFormInput v-model="quantity" id="Quantity" placeholder="Quantity" />
      </BFormFloatingLabel>
       <BFormFloatingLabel label="badge" class="my-2">
        <BFormInput v-model="badge" id="badge" placeholder="badge" />
      </BFormFloatingLabel>
      <BButton type="submit" class="btn-light w-25 text-dark border">tạo mới</BButton>
    </BForm>
  </BContainer>
</template>
