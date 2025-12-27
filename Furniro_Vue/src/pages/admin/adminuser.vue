<script setup>
import { products } from '@/composables/Product.js'
const fields = computed(() => {
  if(!products.length) return []
  const keys = Object.keys(products[0])
  return[...keys.map(key => ({
    key,
  })),
  { key: 'actions', label: '' }]
})
</script>
<style lang="css" >
.bg-warning-2 > tr > th  {
  background-color: #f9f1e7 !important;
  padding: 24px 0;
  font-weight: bold;
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
      <img :src="data.item.image" width="100" height="100" />
    </template>

    <!-- Tuỳ biến cột giá -->
    <template #cell(price)="data"> {{ data.item.price.toLocaleString() }}đ </template>

    <!-- Cột hành động -->
    <template #cell(actions)="data">
      <span style="cursor: pointer">
        <img src="/src/images/cart/trash.png" alt="Xoá" width="24" height="24" />
      </span>
    </template>
  </BTable>
</template>
