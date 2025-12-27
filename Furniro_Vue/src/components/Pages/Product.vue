<script setup>
import api from '@/api'
import { useRoute } from 'vue-router'
import { useCartStore } from '@/composables/useCartStore'
import { ref, reactive, onMounted, watch } from 'vue'
import { useRouter } from 'vue-router/auto'

const router = useRouter()
const route = useRoute()
const cart = useCartStore()

const props = defineProps({
  q: {
    type: String,
    default: '',
  },
})

const pagination = ref({
  current_page: 1,
  last_page: 1,
})

const products = ref([])
const cachedPages = reactive({})

const normalizeProducts = (rawProducts) => {
  return rawProducts.map((product) => {
    let sizeData = []

    if (Array.isArray(product.sizes) && Array.isArray(product.size_quantities)) {
      sizeData = product.sizes.map((size, i) => ({
        size,
        quantity: Number(product.size_quantities[i] || 0),
      }))
    }

    return {
      ...product,
      sizeData,
      image: product.image?.startsWith('http')
        ? product.image
        : `http://localhost:8000/storage/${product.image || ''}`,
      product: product.product || 'Sản phẩm không tên',
      intro: product.intro || '',
      price: product.price || 0,
      price_sale: product.price_sale || null,
      badge: product.badge || null,
    }
  })
}

const getProducts = async (page = 1) => {
  if (props.q.trim()) {
    try {
      const res = await api.get(`/api/search`, { params: { q: props.q } })
      products.value = normalizeProducts(res.data)
      pagination.value = { current_page: 1, last_page: 1 }
    } catch (error) {
      console.error('❌ Lỗi tìm kiếm:', error)
    }
    return
  }

  if (cachedPages[page]) {
    products.value = cachedPages[page]
    pagination.value.current_page = page
    return
  }

  const perPageMap = {
    '/': 8,
    '/shop': 16,
    '/detail_product': 4,
  }

  const perPage = perPageMap[route.path] || 8

  try {
    const res = await api.get(`/api/product`, {
      params: {
        page,
        per_page: perPage,
      },
    })

    const processed = normalizeProducts(res.data.data)
    cachedPages[page] = processed
    products.value = processed
    pagination.value.current_page = res.data.current_page
    pagination.value.last_page = res.data.last_page
  } catch (error) {
    console.error('❌ Lỗi khi lấy sản phẩm:', error)
  }
}

onMounted(() => getProducts())
watch(() => props.q, () => getProducts())
</script>

<template>
  <BRow class="align-items-center">
    <BCol
      v-for="(product, index) in products"
      :key="index"
      cols="12"
      lg="3"
      md="12"
      sm="12"
      class="product position-relative"
    >
      <BCard
        :title="product.product"
        :img-src="product.image"
        img-alt="Image"
        id="image"
        img-top
        tag="article"
        variant="light"
        class="border-0 rounded-0 mb-4 h-100"
      >
        <BCardText>
          <p>{{ product.intro }}</p>
          <div v-if="product.price_sale">
            <BRow class="justify-content-between pt-2 pb-3">
              <BCol cols="12" lg="6" md="6" sm="6">
                <h5>Rp {{ product.price_sale }}</h5>
              </BCol>
              <BCol cols="12" lg="6" md="6" sm="6" class="text-end text-secondary">
                <del> Rp {{ product.price }} </del>
              </BCol>
            </BRow>
          </div>
          <div v-else>
            <BRow class="justify-content-between pt-2 pb-3">
              <BCol cols="12" lg="6" md="6" sm="6">
                <h5>Rp {{ product.price }}</h5>
              </BCol>
            </BRow>
          </div>
        </BCardText>
      </BCard>

      <div
        v-if="product.badge"
        class=""
        :class="product.badge === 'New' ? 'badge-sale2' : 'badge-sale'"
      >
        {{ product.badge }}
      </div>
      <div class="hover-sp">
        <BButton
          @click="router.push(`/product_detail/${product.id}`)"
          class="border-0 rounded-0 text-warning-2 ps-5 pe-5 pt-3 pb-3 mt-5 bg-light"
        >
          <h5>Add to cart</h5>
        </BButton>
        <div class="icon-group pt-2">
          <!-- Share -->
          <a href="">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24">
              <path
                fill="#fff"
                d="M17 22q-1.25 0-2.125-.875T14 19q0-.15.075-.7L7.05 14.2q-.4.375-.925.588T5 15q-1.25 0-2.125-.875T2 12t.875-2.125T5 9q.6 0 1.125.213t.925.587l7.025-4.1q-.05-.175-.062-.337T14 5q0-1.25.875-2.125T17 2t2.125.875T20 5t-.875 2.125T17 8q-.6 0-1.125-.213T14.95 7.2l-7.025 4.1q.05.175.063.338T8 12t-.012.363t-.063.337l7.025 4.1q.4-.375.925-.587T17 16q1.25 0 2.125.875T20 19t-.875 2.125T17 22"
              />
            </svg>
            <span class="p-1"><b>Share</b></span>
          </a>
          <!-- Compare -->
          <a href="">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24">
              <path
                fill="none"
                stroke="#fff"
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M7.5 21L3 16.5m0 0L7.5 12M3 16.5h13.5m0-13.5L21 7.5m0 0L16.5 12M21 7.5H7.5"
              />
            </svg>
            <span class="p-1"><b>Compare</b></span>
          </a>
          <!-- Like -->
          <a href="">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24">
              <path
                fill="#fff"
                d="m12.1 18.55l-.1.1l-.11-.1C7.14 14.24 4 11.39 4 8.5C4 6.5 5.5 5 7.5 5c1.54 0 3.04 1 3.57 2.36h1.86C13.46 6 14.96 5 16.5 5c2 0 3.5 1.5 3.5 3.5c0 2.89-3.14 5.74-7.9 10.05M16.5 3c-1.74 0-3.41.81-4.5 2.08C10.91 3.81 9.24 3 7.5 3C4.42 3 2 5.41 2 8.5c0 3.77 3.4 6.86 8.55 11.53L12 21.35l1.45-1.32C18.6 15.36 22 12.27 22 8.5C22 5.41 19.58 3 16.5 3"
              />
            </svg>
            <span class="p-1"><b>Like</b></span>
          </a>
        </div>
      </div>
    </BCol>
  </BRow>

  <div class="text-center d-flex justify-content-center">
    <BButton
      v-if="route.path === '/'"
      class="border-warning rounded-0 bg-light text-warning-2 ps-5 pe-5 pt-3 pb-2 mt-5"
      to="/shop"
    >
      <h5>Buy now</h5>
    </BButton>
    <div v-else-if="route.path === '/shop' && !props.q">
      <BButton
        v-for="page in pagination.last_page"
        :key="page"
        class="p-3"
        @click="getProducts(page)"
        :class="['pagination-btn', page === pagination.current_page ? 'active' : '']"
      >
        {{ page }}
      </BButton>
      <BButton
        v-if="pagination.current_page < pagination.last_page"
        class="pagination-btn"
        @click="getProducts(pagination.current_page + 1)"
      >
        Next
      </BButton>
      <BButton
        v-else
        class="pagination-btn"
        @click="getProducts(pagination.current_page - 1)"
      >
        Back
      </BButton>
    </div>
  </div>
</template>

<style lang="css">
/* ⭐ STYLE GIỮ NGUYÊN TỪ BẠN ⭐ */
.text-warning-2 {
  color: #b88e2f;
}
.text-warning-2:hover {
  color: #785d1e;
}
.hover-sp {
  position: absolute;
  width: 93%;
  height: 95%;
  background: rgba(0, 0, 0, 0.3);
  top: 0%;
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  transition: all 0.5s;
  transform: scale(0);
  opacity: 0;
}
.hover-sp a {
  color: #fff;
  font-weight: 16px;
  text-decoration: none;
}
.product:hover > .hover-sp {
  transform: scale(1);
  opacity: 1;
}
.badge-sale {
  position: absolute;
  top: 16px;
  right: 24px;
  background-color: #e97171;
  color: white;
  padding: 10px 14px;
  border-radius: 50%;
  font-size: 14px;
  line-height: 1;
  display: flex;
  align-items: center;
  justify-content: center;
  width: 48px;
  height: 48px;
  box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
}
.badge-sale2 {
  position: absolute;
  top: 16px;
  right: 24px;
  background-color: #2ec1ac;
  color: white;
  padding: 10px 14px;
  border-radius: 50%;
  font-size: 14px;
  line-height: 1;
  display: flex;
  align-items: center;
  justify-content: center;
  width: 48px;
  height: 48px;
  box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
}
.bg-success-2 {
  background-color: #2ec1ac;
}
.pagination-btn {
  margin: 0 16px;
  color: #000;
  background-color: #f9f1e7;
  border: none;
  border-radius: 12px;
  font-size: 20px;
  min-width: 60px;
  min-height: 60px;
  text-align: center;
  transition: 0.3s;
  cursor: pointer;
}
.pagination-btn:hover {
  background-color: #b88e2f;
  color: #000;
}
.pagination-btn.active {
  background-color: #b88e2f;
  color: #fff;
}
</style>
