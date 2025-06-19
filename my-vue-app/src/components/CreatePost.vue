<template>
  <div class="max-w-md mx-auto p-4 bg-white rounded shadow">
    <h2 class="text-2xl font-bold mb-4">Create New Post</h2>
    <form @submit.prevent="createPost" class="space-y-4">
      <div>
        <label class="block mb-1" for="image">Image</label>
        <input
          type="file"
          id="image"
          @change="handleImageChange"
          accept="image/*"
          class="w-full border rounded px-3 py-2"
          required
        />
      </div>
      <div>
        <label class="block mb-1" for="caption">Caption</label>
        <textarea
          v-model="form.caption"
          id="caption"
          rows="3"
          class="w-full border rounded px-3 py-2"
          required
        ></textarea>
      </div>
      <button
        type="submit"
        class="w-full bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700"
        :disabled="loading"
      >
        {{ loading ? "Creating..." : "Create Post" }}
      </button>
    </form>
    <p v-if="error" class="text-red-600 mt-2">{{ error }}</p>
  </div>
</template>

<script setup>
import { reactive, ref } from "vue";
import axios from "axios";

const form = reactive({
  caption: "",
  image: null,
});

const loading = ref(false);
const error = ref("");

const handleImageChange = (event) => {
  const file = event.target.files[0];
  if (file) {
    form.image = file;
  }
};

const createPost = async () => {
  if (!form.image || !form.caption.trim()) return;

  loading.value = true;
  error.value = "";

  const formData = new FormData();
  formData.append("image", form.image);
  formData.append("caption", form.caption);

  try {
    const token = localStorage.getItem("token");
    if (!token) throw new Error("Please login first");

    await axios.post("http://localhost:8000/api/posts", formData, {
      headers: {
        "Content-Type": "multipart/form-data",
        Authorization: `Bearer ${token}`,
      },
    });

    // Reset form
    form.caption = "";
    form.image = null;
    const fileInput = document.querySelector("#image");
    if (fileInput) fileInput.value = "";

    alert("Post created successfully!");
  } catch (err) {
    error.value = err.response?.data?.message || "Failed to create post";
  } finally {
    loading.value = false;
  }
};
</script>
