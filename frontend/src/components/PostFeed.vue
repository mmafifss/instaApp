<template>
  <div class="max-w-3xl mx-auto p-4">
    <h2 class="text-2xl font-bold mb-4">Post Feed</h2>
    <div v-if="error" class="text-red-600 mb-4">{{ error }}</div>
    <div v-if="loading" class="text-gray-600">Loading posts...</div>
    <div
      v-for="post in posts"
      :key="post.id"
      class="mb-6 border rounded p-4 bg-white"
    >
      <div class="mb-2 font-semibold">{{ post.user.name }}</div>
      <img
        :src="getImageUrl(post.image_path)"
        alt="Post image"
        class="w-full max-h-96 object-cover mb-2 rounded"
      />
      <div class="mb-2">{{ post.caption }}</div>
      <div class="flex items-center space-x-4">
        <button @click="toggleLike(post)" class="text-blue-600 hover:underline">
          {{ isLiked(post) ? "Unlike" : "Like" }} ({{ post.likes.length }})
        </button>
        <button
          @click="toggleComments(post)"
          class="text-gray-600 hover:underline"
        >
          Comments ({{ post.comments.length }})
        </button>
      </div>
      <div v-if="post.showComments" class="mt-4">
        <div
          v-for="comment in post.comments"
          :key="comment.id"
          class="mb-2 border-b pb-2"
        >
          <div class="font-semibold">{{ comment.user.name }}</div>
          <div>{{ comment.content }}</div>
        </div>
        <form @submit.prevent="addComment(post)">
          <input
            v-model="post.newComment"
            type="text"
            placeholder="Add a comment..."
            class="w-full border rounded px-3 py-2"
          />
          <button
            type="submit"
            class="mt-2 bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700"
          >
            Post Comment
          </button>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive, onMounted } from "vue";
import axios from "axios";

const posts = ref([]);
const loading = ref(false);
const error = ref("");

const getImageUrl = (path) => {
  return `http://localhost:8000/storage/${path}`;
};

const fetchPosts = async () => {
  loading.value = true;
  error.value = "";
  try {
    const response = await axios.get("http://localhost:8000/api/posts");
    posts.value = response.data.map((post) => ({
      ...post,
      showComments: false,
      newComment: "",
    }));
  } catch (err) {
    error.value = "Failed to load posts.";
  } finally {
    loading.value = false;
  }
};

const isLiked = (post) => {
  const token = localStorage.getItem("token");
  if (!token) return false;
  // For simplicity, assume user id is not stored, so just return false
  // In real app, decode token or fetch user info
  return false;
};

const toggleLike = async (post) => {
  const token = localStorage.getItem("token");
  if (!token) {
    alert("Please login to like posts.");
    return;
  }
  try {
    // For simplicity, always send like request
    await axios.post(
      `http://localhost:8000/api/posts/${post.id}/like`,
      {},
      {
        headers: { Authorization: `Bearer ${token}` },
      }
    );
    await fetchPosts();
  } catch (err) {
    alert("Failed to like post.");
  }
};

const toggleComments = (post) => {
  post.showComments = !post.showComments;
};

const addComment = async (post) => {
  const token = localStorage.getItem("token");
  if (!token) {
    alert("Please login to comment.");
    return;
  }
  if (!post.newComment.trim()) return;
  try {
    await axios.post(
      `http://localhost:8000/api/posts/${post.id}/comments`,
      {
        content: post.newComment,
      },
      {
        headers: { Authorization: `Bearer ${token}` },
      }
    );
    post.newComment = "";
    await fetchPosts();
  } catch (err) {
    alert("Failed to add comment.");
  }
};

onMounted(() => {
  fetchPosts();
});
</script>

<style scoped></style>
