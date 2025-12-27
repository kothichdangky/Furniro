
<script setup lang="ts">

</script>
<style scoped>
.bg-warning-2 {
  background-color: #f9f1e7 !important;
}
/* Dùng v-deep để nhắm chính xác vào router-link bên trong component */
::v-deep a.router-link-exact-active {
  border-radius: 12px;
  background-color: #cf9958 !important;
  transition: all 0.9s ease;
  align-items: center;
  width: 100%;
  padding: 24px 0;
}

::v-deep li{
  height: 80px;
}

</style>
<template>
  <BRow>
    <BCol cols="12" lg="3" md="2" sm="2" class="">
      <div class="d-flex flex-column flex-shrink-0 p-0 text-dark bg-warning-2">
        <b-nav pills vertical class="flex-column mb-auto">
          <b-nav-item
            to="/admin/adminshop"
            class=" nav-link d-flex align-items-center p-0 m-0 justify-content-center border-bottom text-center"
          >
            <h4 class="text-dark">Product</h4>
          </b-nav-item>

          <b-nav-item
            to="/admin/adminuser"
            class="nav-link d-flex align-items-center p-0 m-0 justify-content-center border-bottom text-center"
          >
            <h4 class="text-dark">User</h4>
          </b-nav-item>

          <b-nav-item
            to="/admin/createProduct"
            class="nav-link d-flex align-items-center p-0 m-0 justify-content-center border-bottom text-center"
          >
            <h4 class="text-dark">New product</h4>
          </b-nav-item>
        </b-nav>
      </div>
    </BCol>
    <BCol cols="12" lg="9" md="10" sm="10" class="">
    <RouterView v-slot="{ Component }">
      <keep-alive include="AdminShop">
        <component :is="Component" />
      </keep-alive>
    </RouterView>
    </BCol>
  </BRow>
</template>
