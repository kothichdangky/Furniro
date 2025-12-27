<script setup>
import api from '@/api'

import {  useRouter } from 'vue-router/auto'
const router = useRouter()

defineOptions({
  name: 'AdminShop' // tên component để keep-alive nhận diện
})
const pagination = ref({
  current_page: 1,
  last_page: 1
})

const products = ref([])
// tạo cache
// const cachedPages = reactive({})
const getProducts = async (page = 1) => {
// Nếu đã cache trang này → không gọi lại API
  // if (cachedPages[page]) {
  //   products.value = cachedPages[page]
  //   pagination.value.current_page = page
  //   return
  // }
  try {

    const res = await api.get(`/api/shop?page=${page}`)
    // truyền cache
    // cachedPages[page] = res.data.data
    products.value = res.data.data
    pagination.value.current_page = res.data.current_page
    pagination.value.last_page = res.data.last_page
  }
   catch (error) {
    console.error('Lỗi khi lấy dữ liệu sản phẩm:', error)
  }
}
const fields = [
  { key: 'product', label: 'Titlet' },
  { key: 'image', label: 'Image' },
  { key: 'intro', label: 'Description' },
  { key: 'price', label: 'Price' },
  { key: 'price_sale', label: 'Price sale' },
  { key: 'quantity', label: 'Quantity' },
  { key: 'badge', label: 'Badge' },
  { key: 'actions', label: ''},
]


//xoá

const DeleteProducts = async (id) => {
  try {
 const res = await api.delete(`/api/shop/${id}`)
 alert("xoá thành công")
  console.log(res.data.message);
  getProducts(pagination.value.current_page);

  }
   catch (error) {
    console.error('Lỗi khi xoá:', error)
  }
}

onMounted(getProducts)
onActivated(() => getProducts())

</script>
<style lang="css" scopped>
.bg-warning-2 > tr > th  {
  background-color: #f9f1e7 !important;
  padding: 24px 0;
  font-weight: bold;
}

.bg-warning-6  {
  background-color:#e69633
}

.bg-warning-6:hover {
  background-color:#d7bc9b
}


</style>
<template>

  <BTable
    :items="products"
    :fields="fields"
    thead-class="bg-warning-2"
    table-class="my-table table-borderless  align-middle text-center"
  >
    <!-- Tuỳ biến cột ảnh -->
    <template #cell(image)="data">
      <img :src="`http://localhost:8000/storage/${data.item.image}`" width="100" height="100" />
    </template>

    <!-- Tuỳ biến cột giá -->
    <template #cell(price)="data"> {{ data.item.price.toLocaleString() }}đ </template>

    <!-- Cột hành động -->
    <template #cell(actions)="data">
      <span style="cursor: pointer" @click=" DeleteProducts(data.item.id)">
        <img src="/src/images/cart/trash.png" alt="Xoá" width="24" height="24" />
      </span>
      <span style="cursor: pointer"
      class="border p-2 bg-warning-6 "
      @click="router.push(`/admin/edit/${data.item.id}`)">+</span>
    </template>
  </BTable>
  <div class="text-center mt-3">
  <BButton
      variant=""
      class="me-2 bg-warning-6"
      :disabled="pagination.current_page === 1"
      @click="getProducts(pagination.current_page - 1)"

    >
      Trang trước
    </BButton>

    <span class="mx-2">
      Trang {{ pagination.current_page }} / {{ pagination.last_page }}
    </span>

    <BButton
      variant=""
      class="ms-2 bg-warning-6"
      :disabled="pagination.current_page === pagination.last_page"
      @click="getProducts(pagination.current_page + 1)"
    >
      Trang sau
    </BButton>
</div>

</template>
