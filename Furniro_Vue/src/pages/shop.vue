<script setup>
defineOptions({
  name: 'Shop' // tên component để keep-alive nhận diện

})
const route = useRoute()
const keyword = computed(() => route.query.q || '')
</script>
<style>
.poster {
  background-image: url('/src/images/shop/poster.png');
  background-size: cover;
  background-position: center;
  width: 100%;
}
.bg-warning-4 {
  background-color: #f9f1e7;
}

a {
  text-decoration: none;
  color: black;
}

.features {
  display: flex;
  justify-content: space-between;
  flex-wrap: wrap;
}

.feature-item {
  display: flex;
  align-items: center;
  gap: 16px;
  width: auto;
  margin-bottom: 20px;
}

.feature-item b {
  font-size: 24px;
  color: #000;
}

.feature-item p {
  font-size: 20px;
  color: #666;
}
</style>
<template>
  <section class="poster d-flex">
    <BContainer class="text-center py-5 my-5">
      <h1>Shop</h1>
      <div class="mt-3"><span class="fw-bold">Home</span> <span>></span> <span>Shop</span></div>
    </BContainer>
  </section>

  <section class="bg-warning-4">
    <BContainer class="py-5">
      <BRow>
        <BCol cols="12" lg="1" md="3" sm="3" class="align-items-center">
          <a href="" style="font-size: 20px; display: inline-flex; align-items: center; gap: 8px">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
              <path
                fill="#000"
                d="M3 17v2h6v-2zM3 5v2h10V5zm10 16v-2h8v-2h-8v-2h-2v6zM7 9v2H3v2h4v2h2V9zm14 4v-2H11v2zm-6-4h2V7h4V5h-4V3h-2z"
              />
            </svg>
            Filter
          </a>
        </BCol>
        <BCol cols="12" lg="1" md="3" sm="3" class="align-items-center">
          <a href="" style="font-size: 20px; display: inline-flex; align-items: center; gap: 8px">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
              <path
                fill="#000"
                d="M5 8a4 4 0 0 1 4 4a4 4 0 0 1-4 4a4 4 0 0 1-4-4a4 4 0 0 1 4-4m7-7a4 4 0 0 1 4 4a4 4 0 0 1-4 4a4 4 0 0 1-4-4a4 4 0 0 1 4-4m0 14a4 4 0 0 1 4 4a4 4 0 0 1-4 4a4 4 0 0 1-4-4a4 4 0 0 1 4-4m7-7a4 4 0 0 1 4 4a4 4 0 0 1-4 4a4 4 0 0 1-4-4a4 4 0 0 1 4-4"
              />
            </svg>
            <img src="/src/images/shop/bi_view-list.png" alt="" />
          </a>
        </BCol>
        <BCol cols="12" lg="3" md="3" sm="3" class="align-items-center">
          <p href="" style="font-size: 20px; display: inline-flex; align-items: center; gap: 8px">
            Showing 1–16 of 32 results
          </p>
        </BCol>

        <BCol cols="12" lg="2" md="3" sm="3" class="align-items-center"> </BCol>

        <BCol cols="12" lg="2" md="2" sm="2">
          <p
            href=""
            class="pe-2"
            style="font-size: 20px; display: inline-flex; align-items: center"
          >
            show
          </p>
          <input
            type="text"
            class="text-center"
            placeholder="16"
            style="width: 55px; height: 55px"
          />
        </BCol>

        <BCol cols="12" lg="3" md="2" sm="2" class="m-0 p-0">
          <p
            href=""
            class="pe-2"
            style="font-size: 20px; display: inline-flex; align-items: center"
          >
            Short by
          </p>
          <input type="text" class="text-center" placeholder="Default" style="height: 55px" />
        </BCol>
      </BRow>
    </BContainer>
  </section>

  <section class="my-5">
    <BContainer>
      <Product :q="keyword" />
    </BContainer>
  </section>
  <section class="bg-warning-4">
    <div class="features p-5">
      <div class="feature-item">
        <img src="/src/images/shop/trophy 1.png" alt="Trophy" />
        <div>
          <b>High Quality</b>
          <p>Crafted from top materials</p>
        </div>
      </div>
      <div class="feature-item">
        <img src="/src/images/shop/guarantee.png" alt="Warranty" />
        <div>
          <b>Warranty Protection</b>
          <p>Over 2 years</p>
        </div>
      </div>
      <div class="feature-item">
        <img src="/src/images/shop/shipping.png" alt="Shipping" />
        <div>
          <b>Free Shipping</b>
          <p>Order over 150 $</p>
        </div>
      </div>
      <div class="feature-item">
        <img src="/src/images/shop/customer-support.png" alt="Support" />
        <div>
          <b>24 / 7 Support</b>
          <p>Dedicated support</p>
        </div>
      </div>
    </div>
  </section>
</template>
