<script setup>
import { useRoute, useRouter } from 'vue-router/auto'
import api from '@/api'

const route = useRoute('/admin/edit/[id]')
const id = route.params.id
const router = useRouter()

// Form fields
const product = ref('')
const intro = ref('')
const price = ref('')
const price_sale = ref('')
const quantity = ref('')
const badge = ref('')
const image = ref(null)
const addimage = ref([])
const oldimage = ref('')
const oldaddimage = ref([])

// Size + Quantity
const flavors = ref([]) // Tất cả size có thể chọn ['S', 'M', 'L']
const flavorSelected = ref([]) // Danh sách size được chọn
const sizeData = ref([]) // [{ size: 'M', quantity: 2 }, ...]
const allSelected = ref(false)



// Load product data
const getProducts = async () => {
  try {
    const res = await api.get(`/api/edit/${id}`)
    product.value = res.data.product
    price.value = res.data.price
    price_sale.value = res.data.price_sale
    quantity.value = res.data.quantity
    intro.value = res.data.intro
    badge.value = res.data.badge

    const sizes = res.data.sizes || []
    const quantities = res.data.size_quantities || []
    flavors.value = res.data.all_sizes || []
    flavorSelected.value = sizes.filter((_, index) => Number(quantities[index]) > 0) // Tạo danh sách size có quantity > 0

    if (res.data.sizes && res.data.size_quantities) {
    sizeData.value = res.data.sizes.map((size, index) => ({
        size,
        quantity: Number(quantities[index]) || 0,
      }))
    }
    // Gán ảnh chính
    const imagePath = res.data.image
    oldimage.value = imagePath.startsWith('http')
      ? imagePath
      : `http://localhost:8000/storage/${imagePath}`

    // Gán ảnh phụ
    const addimageRaw = res.data.addimage
    oldaddimage.value = Array.isArray(addimageRaw)
      ? addimageRaw.map((path) =>
          path.startsWith('http') ? path : `http://localhost:8000/storage/${path}`,
        )
      : []
  } catch (error) {
    console.error('Lỗi khi lấy dữ liệu sản phẩm:', error)
  }
}

// Đồng bộ sizeData khi chọn size
watch(flavorSelected, (newSizes) => {
  if (sizeData.value.length && newSizes.length === sizeData.value.length) return
  sizeData.value = newSizes.map((size) => {
    const existing = sizeData.value.find((s) => s.size === size)
    return {
      size,
      quantity: existing ? existing.quantity : 0,
    }
  })
  allSelected.value = newSizes.length === flavors.value.length
})

// Toggle Select All
const toggleAll = (checked) => {
  flavorSelected.value = checked ? [...flavors.value] : []
}

// Xoá ảnh phụ
const deleteExtraImage = async (imgUrl, index) => {
  try {
    const path = imgUrl.replace('http://localhost:8000/storage/', '')
    await api.post('/api/delete-extra-image', {
      product_id: id,
      image: path,
    })
    oldaddimage.value.splice(index, 1)
  } catch (err) {
    console.error('Không thể xoá ảnh phụ:', err)
    alert('Xoá ảnh phụ thất bại')
  }
}

// Gửi dữ liệu
const handleProduct = async () => {
  if (!product.value.trim()) {
    alert('⚠ Tên sản phẩm không được để trống')
    return
  }

  if (!price.value || isNaN(Number(price.value))) {
    alert('⚠ Giá sản phẩm phải là số hợp lệ')
    return
  }

  const formData = new FormData()
  formData.append('product', product.value)
  formData.append('intro', intro.value)
  formData.append('badge', badge.value || '')
  formData.append('price', price.value)
  formData.append('price_sale', price_sale.value || '')
  formData.append('quantity', quantity.value || '')

  if (image.value) {
    formData.append('image', image.value)
  }

  addimage.value.forEach((file) => {
    if (file) {
      formData.append('addimage[]', file)
    }
  })

  // Gửi về 1 mảng cho database size
  sizeData.value.forEach((item, i) => {
    formData.append(`sizes[${i}][size]`, item.size)
    formData.append(`sizes[${i}][quantity]`, item.quantity)
  })


  // Dùng hàm cái này vì, laravel giải quyết post tốt hơn so với put
  formData.append('_method', 'PUT')
  try {
    await api.post(`/api/edit/${id}`, formData, {
      headers: {
        'Content-Type': 'multipart/form-data',
      },
    })
    alert(' Cập nhật sản phẩm thành công')
    router.push('/admin/adminshop')
  } catch (err) {
    console.error('Lỗi cập nhật:', err)
    alert(' Cập nhật thất bại')
  }
}

onMounted(getProducts)
</script>
<style scoped>
.delete {
  position: absolute;
  top: -8px;
  right: -8px;
  background: red;
  color: white;
  border: none;
  border-radius: 50%;
  width: 20px;
  height: 20px;
  font-size: 12px;
  cursor: pointer;
  line-height: 17px;
  text-align: center;
}
</style>
<template>
  <BContainer>
    <BForm @submit.prevent="handleProduct">
      <BFormFloatingLabel label="product" class="my-2">
        <BFormInput v-model="product" placeholder="Tên sản phẩm" />
      </BFormFloatingLabel>

      <BFormFile v-model="image" label="Chọn ảnh chính" :state="!!image" accept="image/*" />
      <div v-if="oldimage">
        <label>Ảnh hiện tại:</label><br />
        <img :src="oldimage" style="max-width: 100px; border: 1px solid #ccc" />
      </div>

      <label class="mt-3">Ảnh phụ hiện tại:</label><br />
      <div class="d-flex flex-wrap gap-2">
        <div v-for="(img, i) in oldaddimage" :key="i" style="position: relative">
          <img :src="img" style="max-width: 100px; border: 1px solid #ccc" />
          <button @click="deleteExtraImage(img, i)" class="delete">×</button>
        </div>
      </div>

      <BButton class="btn-warning mt-3 w-25 text-dark border" @click="addimage.push(null)">
        Thêm ảnh phụ
      </BButton>
      <div v-for="(file, index) in addimage" :key="index" class="mt-2">
        <BFormFile
          v-model="addimage[index]"
          :label="`Ảnh phụ ${index + 1}`"
          :state="!!addimage[index]"
          accept="image/*"
        />
      </div>

      <BFormFloatingLabel label="Intro" class="my-2">
        <BFormTextarea v-model="intro" placeholder="Mô tả ngắn" />
      </BFormFloatingLabel>

      <BFormFloatingLabel label="Giá" class="my-2">
        <BFormInput type="number" v-model="price" placeholder="Giá" />
      </BFormFloatingLabel>

      <BFormFloatingLabel label="Giá sale" class="my-2">
        <BFormInput type="number" v-model="price_sale" placeholder="Giá sale" />
      </BFormFloatingLabel>

      <BFormFloatingLabel label="Badge" class="my-2">
        <BFormInput v-model="badge" placeholder="Badge" />
      </BFormFloatingLabel>

      <BFormGroup label="Chọn size:">
        <BFormCheckbox v-model="allSelected" @update:model-value="toggleAll">
          {{ allSelected ? 'Bỏ chọn tất cả' : 'Chọn tất cả' }}
        </BFormCheckbox>

        <BFormCheckboxGroup
          v-model="flavorSelected"
          :options="flavors"
          label="Using options array:"
          class="mt-2"
        />
      </BFormGroup>

      <div v-for="(item, index) in sizeData" :key="item.size" class="mt-2">
        <BFormFloatingLabel :label="`Số lượng cho size ${item.size}`">
          <BFormInput
            type="number"
            min="0"
            v-model.number="sizeData[index].quantity"
            placeholder="Nhập số lượng"
          />
        </BFormFloatingLabel>
      </div>

      <div label="Quantity" class="my-2">
       Quantity: {{ quantity }}
      </div>

      <BButton type="submit" class="btn-light w-25 text-dark border mt-3">Cập nhật</BButton>
    </BForm>
  </BContainer>
</template>


