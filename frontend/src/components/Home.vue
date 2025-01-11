<template>
  <div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h1>Blog List</h1>
      <div>
        <button class="btn btn-primary me-2" @click="goToCreateBlog">Create New Blog</button>
        <button class="btn btn-danger" @click="handleLogout">Logout</button>
      </div>
    </div>
    <div class="mb-3">
      <label for="sort" class="form-label">Sort by:</label>
      <select id="sort" v-model="sortDirection" class="form-select" @change="fetchBlogs">
        <option value="asc">Ascending</option>
        <option value="desc">Descending</option>
      </select>
    </div>
    <ul class="list-group">
      <li v-for="blog in blogs" :key="blog.id" class="list-group-item" @click="goToBlog(blog.id)">
        <div class="d-flex">
          <img :src="baseUrl + blog.image" alt="Blog Image" class="img-thumbnail me-3" style="width: 100px; height: 100px;">
          <div>
            <h5>{{ blog.title }}</h5>
            <p>{{ blog.content.substring(0, 100) }} ...</p>
          </div>
        </div>
      </li>
    </ul>
    <nav aria-label="Page navigation" class="mt-3">
      <ul class="pagination">
        <li class="page-item" :class="{ disabled: currentPage === 1 }">
          <a class="page-link" @click="changePage(currentPage - 1)">Previous</a>
        </li>
        <li class="page-item" v-for="page in totalPages" :key="page" :class="{ active: currentPage === page }">
          <a class="page-link" @click="changePage(page)">{{ page }}</a>
        </li>
        <li class="page-item" :class="{ disabled: currentPage === totalPages }">
          <a class="page-link" @click="changePage(currentPage + 1)">Next</a>
        </li>
      </ul>
    </nav>
  </div>
</template>

<script>
import { ref, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import api from '../axios';
import { backendUrl } from '../axios';

export default {
  name: 'HomePage',
  setup() {
    const blogs = ref([]);
    const currentPage = ref(1);
    const totalPages = ref(1);
    const baseUrl = ref(backendUrl);
    const sortDirection = ref('asc');
    const router = useRouter();

    const fetchBlogs = async () => {
      try {
        const response = await api.get('/blogs', {
          params: {
            page: currentPage.value,
            sort_direction: sortDirection.value,
          },
        });
        blogs.value = response.data.data;
        totalPages.value = response.data.meta.last_page;
        baseUrl.value = backendUrl;
      } catch (error) {
        console.error('Error fetching blogs:', error);
      }
    };

    const changePage = (page) => {
      if (page > 0 && page <= totalPages.value) {
        currentPage.value = page;
        fetchBlogs();
      }
    };

    const goToCreateBlog = () => {
      router.push('/create-blog');
    };

    const handleLogout = () => {
      localStorage.removeItem('auth_token');
      router.push('/login');
    };


    const goToBlog = (id) => {
      router.push(`/blog/${id}`);
    };

    onMounted(fetchBlogs);

    return {
      blogs,
      currentPage,
      totalPages,
      baseUrl,
      sortDirection,
      changePage,
      goToCreateBlog,
      handleLogout,
      fetchBlogs,
      goToBlog,
    };
  },
};
</script>

<style scoped>
.container {
  max-width: 800px;
}

.img-thumbnail {
  object-fit: cover;
}
</style>
