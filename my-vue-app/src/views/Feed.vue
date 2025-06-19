<template>
  <div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-2xl mx-auto px-4">
      <!-- Create Post Component -->
      <div
        class="bg-white rounded-xl shadow-sm border border-gray-200 mb-6 p-6"
      >
        <div class="flex items-start space-x-3">
          <div
            class="w-10 h-10 bg-gradient-to-r from-purple-500 to-blue-500 rounded-full flex items-center justify-center"
          >
            <span class="text-white font-semibold text-sm">
              {{ currentUser?.name?.charAt(0)?.toUpperCase() || "U" }}
            </span>
          </div>
          <div class="flex-1">
            <textarea
              v-model="newPostContent"
              placeholder="What's on your mind?"
              class="w-full p-3 border border-gray-300 rounded-lg resize-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
              rows="3"
            ></textarea>
            <div class="flex items-center justify-between mt-3">
              <div>
                <input
                  type="file"
                  ref="fileInput"
                  accept="image/*"
                  @change="handleFileUpload"
                  class="hidden"
                />
                <button
                  @click="$refs.fileInput.click()"
                  class="text-gray-500 hover:text-blue-500 p-2 rounded-full hover:bg-gray-100"
                >
                  <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path
                      fill-rule="evenodd"
                      d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z"
                      clip-rule="evenodd"
                    />
                  </svg>
                </button>
                <span v-if="selectedFile" class="text-sm text-gray-500 ml-2">
                  {{ selectedFile.name }}
                </span>
              </div>
              <button
                @click="createPost"
                :disabled="!newPostContent || posting"
                class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors duration-200 font-medium disabled:opacity-50 disabled:cursor-not-allowed"
              >
                <span v-if="posting">Posting...</span>
                <span v-else>Post</span>
              </button>
            </div>
            <div v-if="previewImage" class="mt-3">
              <img
                :src="previewImage"
                alt="Preview"
                class="max-h-40 rounded-lg"
              />
              <button
                @click="removeImage"
                class="mt-2 text-red-500 text-sm flex items-center"
              >
                <svg
                  class="w-4 h-4 mr-1"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
                  />
                </svg>
                Remove Image
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- Rest of your existing template remains the same -->
      <!-- ... -->
    </div>
  </div>
</template>

<script>
import { ref, reactive, onMounted, onUnmounted } from "vue";
import CreatePost from "../components/CreatePost.vue";
import axios from "axios";

export default {
  name: "Feed",
  setup() {
    // Reactive data
    const posts = ref([]);
    const loading = ref(false);
    const loadingMore = ref(false);
    const error = ref("");
    const hasMorePosts = ref(true);
    const expandedComments = ref([]);
    const activeMenu = ref(null);
    const imageModal = ref(null);
    const newComments = reactive({});
    const currentPage = ref(1);
    const newPostContent = ref("");
    const selectedFile = ref(null);
    const previewImage = ref(null);
    const posting = ref(false);
    const fileInput = ref(null);

    // Mock current user
    const currentUser = ref({
      id: 1,
      name: "Current User",
      avatar: null,
    });

    // Add these new methods
    const handleFileUpload = (event) => {
      const file = event.target.files[0];
      if (file) {
        selectedFile.value = file;
        previewImage.value = URL.createObjectURL(file);
      }
    };

    const removeImage = () => {
      selectedFile.value = null;
      previewImage.value = null;
      if (fileInput.value) {
        fileInput.value.value = "";
      }
    };

    const createPost = async () => {
      if (!newPostContent.value.trim()) return;

      posting.value = true;
      error.value = "";

      try {
        const formData = new FormData();
        formData.append("caption", newPostContent.value);
        if (selectedFile.value) {
          formData.append("image", selectedFile.value);
        }

        const token = localStorage.getItem("token");
        if (!token) throw new Error("Please login first");

        const response = await axios.post(
          "http://localhost:8000/api/posts",
          formData,
          {
            headers: {
              "Content-Type": "multipart/form-data",
              Authorization: `Bearer ${token}`,
            },
          }
        );

        if (!response.ok) {
          throw new Error("Failed to create post");
        }

        const newPost = await response.json();

        // Add the new post to the beginning of the posts array
        posts.value.unshift({
          ...newPost,
          user: currentUser.value, // Assuming currentUser is set
          isLiked: false,
          isSaved: false,
          comments_count: 0,
          likes_count: 0,
        });

        // Reset form
        newPostContent.value = "";
        removeImage();
      } catch (err) {
        error.value = "Failed to create post. Please try again.";
        console.error("Error creating post:", err);
      } finally {
        posting.value = false;
      }
    };

    const deletePost = async (postId) => {
      if (!confirm("Are you sure you want to delete this post?")) return;

      try {
        const response = await axios.delete(
          `http://localhost:8000/api/posts/${postId}`
        );

        if (!response.ok) {
          throw new Error("Failed to delete post");
        }

        // Remove the post from the local array
        posts.value = posts.value.filter((post) => post.id !== postId);
      } catch (err) {
        error.value = "Failed to delete post. Please try again.";
        console.error("Error deleting post:", err);
      }
    };

    // Modify the toggleMenu method to include delete option for own posts

    // Fetch posts from API
    const fetchPosts = async (page = 1) => {
      try {
        loading.value = page === 1;
        loadingMore.value = page > 1;

        // Mock API call - replace with your actual API endpoint
        const response = await fetch(`/api/posts?page=${page}&limit=10`);
        const data = await response.json();

        if (page === 1) {
          posts.value = data.posts;
        } else {
          posts.value.push(...data.posts);
        }

        hasMorePosts.value = data.hasMore;
        currentPage.value = page;
      } catch (err) {
        error.value = "Failed to load posts. Please try again.";
        console.error("Error fetching posts:", err);
      } finally {
        loading.value = false;
        loadingMore.value = false;
      }
    };

    // Load more posts
    const loadMorePosts = () => {
      fetchPosts(currentPage.value + 1);
    };

    // Toggle like on post
    const toggleLike = async (post) => {
      try {
        const wasLiked = post.isLiked;
        post.isLiked = !wasLiked;
        post.likes_count = (post.likes_count || 0) + (wasLiked ? -1 : 1);

        // API call to toggle like
        await fetch(`/api/posts/${post.id}/like`, {
          method: wasLiked ? "DELETE" : "POST",
        });
      } catch (err) {
        // Revert on error
        post.isLiked = !post.isLiked;
        post.likes_count = (post.likes_count || 0) + (post.isLiked ? 1 : -1);
        console.error("Error toggling like:", err);
      }
    };

    // Toggle comments visibility
    const toggleComments = async (postId) => {
      const index = expandedComments.value.indexOf(postId);
      if (index === -1) {
        expandedComments.value.push(postId);
        await loadComments(postId);
      } else {
        expandedComments.value.splice(index, 1);
      }
    };

    // Load comments for a post
    const loadComments = async (postId) => {
      try {
        const response = await fetch(`/api/posts/${postId}/comments`);
        const comments = await response.json();

        const post = posts.value.find((p) => p.id === postId);
        if (post) {
          post.comments = comments;
        }
      } catch (err) {
        console.error("Error loading comments:", err);
      }
    };

    // Add new comment
    const addComment = async (postId) => {
      const content = newComments[postId]?.trim();
      if (!content) return;

      try {
        const response = await fetch(`/api/posts/${postId}/comments`, {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify({ content }),
        });

        const newComment = await response.json();

        const post = posts.value.find((p) => p.id === postId);
        if (post) {
          post.comments = post.comments || [];
          post.comments.unshift(newComment);
          post.comments_count = (post.comments_count || 0) + 1;
        }

        newComments[postId] = "";
      } catch (err) {
        console.error("Error adding comment:", err);
      }
    };

    // Share post
    const sharePost = (post) => {
      if (navigator.share) {
        navigator.share({
          title: `Post by ${post.user.name}`,
          text: post.content,
          url: window.location.href,
        });
      } else {
        // Fallback - copy to clipboard
        navigator.clipboard.writeText(window.location.href);
        alert("Link copied to clipboard!");
      }
    };

    // Save post
    const savePost = async (post) => {
      try {
        post.isSaved = !post.isSaved;

        await fetch(`/api/posts/${post.id}/save`, {
          method: post.isSaved ? "POST" : "DELETE",
        });
      } catch (err) {
        post.isSaved = !post.isSaved;
        console.error("Error saving post:", err);
      }
    };

    // Report post
    const reportPost = (post) => {
      // Implement report functionality
      alert("Post reported. Thank you for helping keep our community safe.");
      activeMenu.value = null;
    };

    // Toggle menu
    const toggleMenu = (post) => {
      activeMenu.value = activeMenu.value === post.id ? null : post.id;
    };

    // Image modal functions
    const openImageModal = (imageUrl) => {
      imageModal.value = imageUrl;
    };

    const closeImageModal = () => {
      imageModal.value = null;
    };

    // Like comment
    const likeComment = async (comment) => {
      try {
        const wasLiked = comment.isLiked;
        comment.isLiked = !wasLiked;
        comment.likes_count = (comment.likes_count || 0) + (wasLiked ? -1 : 1);

        await fetch(`/api/comments/${comment.id}/like`, {
          method: wasLiked ? "DELETE" : "POST",
        });
      } catch (err) {
        comment.isLiked = !comment.isLiked;
        comment.likes_count =
          (comment.likes_count || 0) + (comment.isLiked ? 1 : -1);
        console.error("Error liking comment:", err);
      }
    };

    // Format date
    const formatDate = (dateString) => {
      const date = new Date(dateString);
      const now = new Date();
      const diffInHours = Math.floor((now - date) / (1000 * 60 * 60));

      if (diffInHours < 1) return "Just now";
      if (diffInHours < 24) return `${diffInHours}h ago`;
      if (diffInHours < 168) return `${Math.floor(diffInHours / 24)}d ago`;

      return date.toLocaleDateString();
    };

    // Close menu when clicking outside
    const handleClickOutside = (event) => {
      if (!event.target.closest(".relative")) {
        activeMenu.value = null;
      }
    };

    // Lifecycle hooks
    onMounted(() => {
      fetchPosts();
      document.addEventListener("click", handleClickOutside);
    });

    onUnmounted(() => {
      document.removeEventListener("click", handleClickOutside);
    });

    return {
      posts,
      loading,
      loadingMore,
      error,
      hasMorePosts,
      expandedComments,
      activeMenu,
      imageModal,
      newComments,
      currentUser,
      fetchPosts,
      loadMorePosts,
      toggleLike,
      toggleComments,
      addComment,
      sharePost,
      savePost,
      reportPost,
      toggleMenu,
      openImageModal,
      closeImageModal,
      likeComment,
      formatDate,
      newPostContent,
      selectedFile,
      previewImage,
      posting,
      fileInput,
      handleFileUpload,
      removeImage,
      createPost,
      deletePost,
    };
  },
};
</script>
