<template>
  <div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-2xl mx-auto px-4">
      <!-- Header -->
      <div class="text-center mb-8">
        <h1
          class="text-4xl font-bold bg-gradient-to-r from-purple-600 to-blue-600 bg-clip-text text-transparent mb-2"
        >
          Social Feed
        </h1>
        <p class="text-gray-600">Discover what's happening in your community</p>
      </div>

      <!-- Error Message -->
      <div
        v-if="error"
        class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6"
      >
        <div class="flex">
          <div class="flex-shrink-0">
            <svg
              class="h-5 w-5 text-red-400"
              viewBox="0 0 20 20"
              fill="currentColor"
            >
              <path
                fill-rule="evenodd"
                d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                clip-rule="evenodd"
              />
            </svg>
          </div>
          <div class="ml-3">
            <p class="text-red-800">{{ error }}</p>
          </div>
        </div>
      </div>

      <!-- Loading State -->
      <div
        v-if="loading"
        class="flex flex-col items-center justify-center py-12"
      >
        <div class="relative text-center">
          <div
            class="w-12 h-12 rounded-full border-4 border-purple-200 border-t-purple-600 animate-spin mx-auto"
          ></div>
          <p class="text-gray-600 mt-4 text-center">Loading posts...</p>
        </div>
      </div>

      <!-- Posts -->
      <div class="space-y-6">
        <div
          v-for="post in posts"
          :key="post.id"
          class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden transition-all duration-200 hover:shadow-md"
        >
          <!-- Post Header -->
          <div class="p-6 pb-4">
            <div class="flex items-center space-x-3">
              <div
                class="w-12 h-12 bg-gradient-to-r from-purple-500 to-blue-500 rounded-full flex items-center justify-center"
              >
                <span class="text-white font-semibold text-lg">
                  {{ post.user.name.charAt(0).toUpperCase() }}
                </span>
              </div>
              <div class="flex-1">
                <h3 class="font-semibold text-gray-900">
                  {{ post.user.name }}
                </h3>
                <p class="text-gray-500 text-sm">
                  {{ formatDate(post.created_at) }}
                </p>
              </div>
              <div class="flex items-center space-x-2">
                <button
                  @click="toggleLike(post)"
                  class="text-gray-400 hover:text-red-500 transition-colors duration-200"
                  :class="{ 'text-red-500': post.isLiked }"
                >
                  <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path
                      fill-rule="evenodd"
                      d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z"
                      clip-rule="evenodd"
                    />
                  </svg>
                </button>
                <div class="relative">
                  <button
                    @click="toggleMenu(post.id)"
                    class="text-gray-400 hover:text-gray-600 transition-colors duration-200 p-1 rounded-full hover:bg-gray-100"
                  >
                    <svg
                      class="w-5 h-5"
                      fill="currentColor"
                      viewBox="0 0 20 20"
                    >
                      <path
                        d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"
                      />
                    </svg>
                  </button>
                  <div
                    v-if="activeMenu === post.id"
                    class="absolute right-0 top-8 bg-white rounded-lg shadow-lg border border-gray-200 py-2 z-10 min-w-32"
                  >
                    <button
                      @click="sharePost(post)"
                      class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 w-full text-left"
                    >
                      <svg
                        class="w-4 h-4 mr-2"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                      >
                        <path
                          stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z"
                        />
                      </svg>
                      Share
                    </button>
                    <button
                      @click="reportPost(post)"
                      class="flex items-center px-4 py-2 text-sm text-red-600 hover:bg-gray-50 w-full text-left"
                    >
                      <svg
                        class="w-4 h-4 mr-2"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                      >
                        <path
                          stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.464 0L4.35 16.5c-.77.833.192 2.5 1.732 2.5z"
                        />
                      </svg>
                      Report
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Post Content -->
          <div class="px-6 pb-4">
            <p class="text-gray-800 text-lg leading-relaxed mb-4">
              {{ post.content }}
            </p>

            <!-- Post Image -->
            <div v-if="post.image" class="mb-4">
              <img
                :src="post.image"
                :alt="post.content"
                class="w-full rounded-lg cursor-pointer hover:opacity-95 transition-opacity duration-200"
                @click="openImageModal(post.image)"
              />
            </div>

            <!-- Post Tags -->
            <div
              v-if="post.tags && post.tags.length"
              class="flex flex-wrap gap-2 mb-4"
            >
              <span
                v-for="tag in post.tags"
                :key="tag"
                class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-sm font-medium"
              >
                #{{ tag }}
              </span>
            </div>
          </div>

          <!-- Post Actions -->
          <div class="px-6 py-4 border-t border-gray-100">
            <div class="flex items-center justify-between">
              <div class="flex items-center space-x-6">
                <button
                  @click="toggleLike(post)"
                  class="flex items-center text-gray-600 hover:text-blue-600 transition-colors duration-200 font-medium"
                  :class="{ 'text-red-500': post.isLiked }"
                >
                  <svg
                    class="w-5 h-5 mr-1"
                    fill="currentColor"
                    viewBox="0 0 20 20"
                  >
                    <path
                      fill-rule="evenodd"
                      d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z"
                      clip-rule="evenodd"
                    />
                  </svg>
                  {{ post.likes_count || 0 }}
                </button>

                <button
                  @click="toggleComments(post.id)"
                  class="flex items-center text-gray-600 hover:text-blue-600 transition-colors duration-200 font-medium"
                >
                  <svg
                    class="w-5 h-5 mr-1"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"
                    />
                  </svg>
                  {{ post.comments_count || 0 }}
                </button>

                <button
                  @click="sharePost(post)"
                  class="flex items-center text-gray-600 hover:text-blue-600 transition-colors duration-200 font-medium"
                >
                  <svg
                    class="w-5 h-5 mr-1"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z"
                    />
                  </svg>
                  Share
                </button>
              </div>

              <button
                @click="savePost(post)"
                class="flex items-center text-gray-600 hover:text-blue-600 transition-colors duration-200 font-medium"
                :class="{ 'text-blue-500': post.isSaved }"
              >
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                  <path d="M5 4a2 2 0 012-2h6a2 2 0 012 2v14l-5-2.5L5 18V4z" />
                </svg>
              </button>
            </div>
          </div>

          <!-- Comments Section -->
          <div
            v-if="expandedComments.includes(post.id)"
            class="border-t border-gray-100"
          >
            <div class="p-6">
              <!-- Add Comment -->
              <div class="flex space-x-3 mb-4">
                <div
                  class="w-8 h-8 bg-gradient-to-r from-purple-500 to-blue-500 rounded-full flex items-center justify-center"
                >
                  <span class="text-white font-semibold text-sm">
                    {{ currentUser?.name?.charAt(0)?.toUpperCase() || "U" }}
                  </span>
                </div>
                <div class="flex-1">
                  <textarea
                    v-model="newComments[post.id]"
                    @keydown.enter.prevent="addComment(post.id)"
                    placeholder="Write a comment..."
                    class="w-full p-3 border border-gray-300 rounded-lg resize-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    rows="2"
                  ></textarea>
                  <div class="flex justify-end mt-2">
                    <button
                      @click="addComment(post.id)"
                      class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors duration-200 font-medium"
                    >
                      Post Comment
                    </button>
                  </div>
                </div>
              </div>

              <!-- Comments List -->
              <div class="space-y-4">
                <div
                  v-for="comment in post.comments"
                  :key="comment.id"
                  class="border-b border-gray-100 last:border-b-0 pb-3 last:pb-0"
                >
                  <div class="flex space-x-3">
                    <div class="user-avatar-small">
                      <span class="text-white font-semibold text-sm">
                        {{ comment.user.name.charAt(0).toUpperCase() }}
                      </span>
                    </div>
                    <div class="flex-1">
                      <div class="bg-gray-50 rounded-lg p-3">
                        <h4 class="font-semibold text-gray-900 text-sm">
                          {{ comment.user.name }}
                        </h4>
                        <p class="text-gray-800 mt-1">{{ comment.content }}</p>
                      </div>
                      <div class="flex items-center space-x-4 mt-2">
                        <span class="text-xs text-gray-500">{{
                          formatDate(comment.created_at)
                        }}</span>
                        <button
                          @click="likeComment(comment)"
                          class="text-xs text-gray-500 hover:text-blue-500"
                        >
                          Like ({{ comment.likes_count || 0 }})
                        </button>
                        <button
                          class="text-xs text-gray-500 hover:text-blue-500"
                        >
                          Reply
                        </button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Load More Button -->
      <div v-if="hasMorePosts" class="text-center mt-8">
        <button
          @click="loadMorePosts"
          :disabled="loadingMore"
          class="bg-white border-2 border-purple-200 text-purple-600 px-8 py-3 rounded-full font-semibold hover:bg-purple-50 hover:border-purple-300 transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
        >
          <span v-if="loadingMore">Loading...</span>
          <span v-else>Load More Posts</span>
        </button>
      </div>
    </div>

    <!-- Image Modal -->
    <div
      v-if="imageModal"
      @click="closeImageModal"
      class="fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-50 p-4"
    >
      <div class="relative max-w-4xl max-h-full" @click.stop>
        <img
          :src="imageModal"
          alt="Post image"
          class="max-w-full max-h-full object-contain"
        />
        <button
          @click="closeImageModal"
          class="absolute top-4 right-4 bg-black bg-opacity-50 text-white p-2 rounded-full hover:bg-opacity-75 transition-all duration-200"
        >
          <svg
            class="w-6 h-6"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M6 18L18 6M6 6l12 12"
            />
          </svg>
        </button>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, reactive, onMounted, onUnmounted } from "vue";
import CreatePost from "../components/CreatePost.vue";

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

    // Mock current user
    const currentUser = ref({
      id: 1,
      name: "Current User",
      avatar: null,
    });

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
    const toggleMenu = (postId) => {
      activeMenu.value = activeMenu.value === postId ? null : postId;
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
    };
  },
};
</script>

<style scoped>
/* You must move all Tailwind utility classes to the template elements directly.
   Below are only minimal custom styles if needed. Remove @apply usage. */

/* Example: If you need to add custom styles, do so here. Otherwise, use Tailwind classes in the template. */
</style>
