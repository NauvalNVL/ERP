<template>
  <AppLayout header="Define User Access Permission" :show-header="false">
    <Head title="Define User Access Permission" />
    <div
      class="min-h-screen bg-white md:bg-gradient-to-br md:from-indigo-50 md:via-white md:to-purple-50 py-8 px-4 sm:px-6 lg:px-8 relative overflow-x-hidden"
    >
      <div class="max-w-6xl w-full mx-auto relative z-100">
        <!-- Header Card -->
        <div
          class="bg-gradient-to-r from-indigo-600 via-indigo-500 to-purple-600 text-white shadow-lg rounded-2xl border border-indigo-700 mb-6"
        >
          <div class="px-4 py-3 sm:px-6 flex items-center gap-3">
            <div
              class="h-9 w-9 rounded-full bg-indigo-500 flex items-center justify-center"
            >
              <ShieldCheckIcon class="h-5 w-5 text-white" />
            </div>
            <div>
              <h2 class="text-lg sm:text-xl font-semibold leading-tight">
                Define User Access Permission
              </h2>
              <p class="text-xs sm:text-sm text-indigo-100">
                Define and manage user permissions for system access
              </p>
            </div>
          </div>
        </div>

        <!-- Search User Section -->
        <div
          class="bg-white shadow-sm rounded-xl border border-gray-200 overflow-hidden mb-4"
        >
          <div
            class="px-4 py-3 sm:px-6 border-b border-indigo-600 bg-gradient-to-r from-indigo-600 to-blue-600 text-white"
          >
            <h2 class="text-sm font-semibold flex items-center gap-2">
              <span
                class="inline-flex h-7 w-7 items-center justify-center rounded-full bg-white/20"
              >
                <UserIcon class="h-4 w-4 text-white" />
              </span>
              <span>Find User</span>
            </h2>
            <p class="text-xs text-indigo-100 mt-1">
              Search for a user to define permissions.
            </p>
          </div>
          <div class="px-4 py-3 sm:px-6 sm:py-4">
            <form @submit.prevent="searchUser" class="space-y-2">
              <div class="flex-1">
                <label
                  for="search_user_id"
                  class="block text-sm sm:text-base font-semibold text-gray-800"
                >
                  User ID
                </label>
                <div class="mt-1 flex flex-col sm:flex-row sm:items-center gap-3">
                  <div class="relative flex-1">
                    <div
                      class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3"
                    >
                      <IdentificationIcon class="h-4 w-4 text-gray-400" />
                    </div>
                    <input
                      id="search_user_id"
                      type="text"
                      v-model="searchForm.user_id"
                      class="block w-full rounded-md border border-gray-300 bg-white py-2 pl-9 pr-3 text-sm text-gray-900 placeholder-indigo-200 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500"
                      placeholder="Enter User ID (e.g. ADMIN001, USER001)"
                      required
                    />
                  </div>
                  <button
                    type="submit"
                    :disabled="isSearching"
                    class="inline-flex items-center justify-center rounded-lg bg-gradient-to-r from-blue-600 to-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:from-blue-700 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 disabled:opacity-50 disabled:cursor-not-allowed"
                  >
                    <SearchIcon class="h-4 w-4 mr-2" />
                    <span>{{ isSearching ? "Searching..." : "Search User" }}</span>
                  </button>
                  <button
                    type="button"
                    @click="openUserModal"
                    :disabled="isLoadingUsers"
                    class="inline-flex items-center justify-center rounded-lg border border-indigo-300 bg-white px-4 py-2 text-sm font-medium text-indigo-700 shadow-sm hover:bg-indigo-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 disabled:opacity-50 disabled:cursor-not-allowed"
                  >
                    <span>{{ isLoadingUsers ? "Loading..." : "Browse" }}</span>
                  </button>
                </div>
                <p class="mt-1 text-xs text-gray-500">
                  The user's current permissions will load automatically after a
                  successful search.
                </p>
              </div>
            </form>
          </div>
        </div>

        <!-- Search Results -->
        <div v-if="searchMessage" class="mb-4">
          <div
            v-if="searchMessageType === 'success'"
            class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg shadow-sm"
          >
            <div class="flex items-center">
              <component :is="searchMessageIcon" class="h-5 w-5 text-green-500 mr-2" />
              <p class="text-sm font-medium">{{ searchMessage }}</p>
            </div>
          </div>
          <div
            v-else-if="searchMessageType === 'warning'"
            class="bg-yellow-50 border border-yellow-200 text-yellow-800 px-4 py-3 rounded-lg shadow-sm"
          >
            <div class="flex items-center">
              <component :is="searchMessageIcon" class="h-5 w-5 text-yellow-500 mr-2" />
              <p class="text-sm font-medium">{{ searchMessage }}</p>
            </div>
          </div>
          <div
            v-else
            class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg shadow-sm"
          >
            <div class="flex items-center">
              <component :is="searchMessageIcon" class="h-5 w-5 text-red-500 mr-2" />
              <p class="text-sm font-medium">{{ searchMessage }}</p>
            </div>
          </div>
        </div>

        <UserModal
          :show="showUserModal"
          :users="users"
          id-key="user_id"
          name-key="official_name"
          job-key="official_title"
          :enable-job-filter="true"
          :show-job-column="true"
          @close="showUserModal = false"
          @select="onUserSelected"
        />

        <!-- User Permission Form -->
        <div
          v-if="foundUser"
          class="bg-white shadow-sm rounded-xl border border-gray-200 overflow-hidden"
        >
          <!-- User Info Header -->
          <div
            class="bg-gradient-to-r from-indigo-600 via-indigo-500 to-purple-600 px-4 py-3 sm:px-6 md:py-4 border-b border-indigo-700"
          >
            <div class="flex flex-col lg:flex-row items-center justify-between">
              <div class="flex items-center mb-4 lg:mb-0">
                <div
                  class="w-16 h-16 md:w-20 md:h-20 bg-white/20 rounded-full flex items-center justify-center text-white font-bold text-2xl mr-4 md:mr-6"
                >
                  {{ foundUser.official_name.charAt(0).toUpperCase() }}
                </div>
                <div class="text-center lg:text-left">
                  <h3 class="text-lg md:text-xl font-bold text-white mb-1">
                    {{ foundUser.official_name }}
                  </h3>
                  <p class="text-xs text-blue-100">
                    {{ foundUser.user_id }} • {{ foundUser.official_title || "No Title" }}
                  </p>
                  <p class="text-xs text-blue-100 mt-2">
                    Status:
                    <span
                      class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold"
                      :class="
                        foundUser.status === 'A'
                          ? 'bg-emerald-500 text-white'
                          : 'bg-red-500 text-white'
                      "
                    >
                      {{ foundUser.status === "A" ? "Active" : "Inactive" }}
                    </span>
                  </p>
                </div>
              </div>
              <div class="text-center lg:text-right">
                <div class="bg-indigo-500/40 rounded-xl px-4 py-3">
                  <p class="text-xs text-indigo-100 mb-1">Total Permissions</p>
                  <p class="text-2xl md:text-3xl font-bold text-white">
                    {{ selectedPermissionsCount }}
                  </p>
                  <p class="text-[11px] text-indigo-100">
                    of {{ totalPermissionsCount }}
                  </p>
                </div>
              </div>
            </div>
          </div>
          <div class="p-4 md:p-8">
            <form @submit.prevent="savePermissions" class="space-y-8">
              <!-- Select All Toggle -->
              <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 mb-8">
                <div class="flex items-center justify-between">
                  <div>
                    <h3 class="text-lg font-semibold text-gray-900">Quick Actions</h3>
                    <p class="text-sm text-gray-600">
                      Select or clear every permission with a single toggle
                    </p>
                  </div>
                  <div class="flex items-center space-x-4">
                    <SwitchGroup>
                      <div class="flex items-center">
                        <SwitchLabel class="mr-3 text-sm font-medium text-gray-700"
                          >Select All</SwitchLabel
                        >
                        <Switch
                          v-model="selectAllPermissions"
                          @click="toggleAllPermissions"
                          :class="[
                            selectAllPermissions ? 'bg-indigo-600' : 'bg-gray-200',
                            'relative inline-flex h-6 w-11 items-center rounded-full transition-colors focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2',
                          ]"
                        >
                          <span
                            :class="[
                              selectAllPermissions ? 'translate-x-6' : 'translate-x-1',
                              'inline-block h-4 w-4 transform rounded-full bg-white transition-transform',
                            ]"
                          />
                        </Switch>
                      </div>
                    </SwitchGroup>
                  </div>
                </div>
              </div>

              <!-- Permission Categories -->
              <div class="space-y-6">
                <!-- Dashboard -->
                <div
                  class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden"
                >
                  <div
                    class="dashboard-header bg-gradient-to-r from-indigo-600 to-indigo-700 px-4 md:px-6 py-4 border-b border-indigo-800"
                  >
                    <h4 class="font-semibold text-white flex items-center">
                      <i class="fas fa-tachometer-alt w-5 h-5 mr-3 text-indigo-200"></i>
                      Dashboard
                      <span class="ml-auto text-sm text-indigo-200"
                        >({{ getSelectedCountForCategory("dashboard") }}/{{
                          getCategoryPermissions("dashboard").length
                        }})</span
                      >
                    </h4>
                  </div>
                  <div class="p-4 md:p-6">
                    <label class="flex items-center">
                      <input
                        type="checkbox"
                        v-model="form.permissions.dashboard"
                        class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                      />
                      <span class="ml-3 text-sm text-gray-700">Dashboard Access</span>
                    </label>
                  </div>
                </div>

                <!-- System Manager -->
                <div
                  class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden"
                >
                  <button
                    type="button"
                    @click="toggleCategory('system_manager')"
                    class="system-manager-header w-full bg-gradient-to-r from-indigo-600 to-indigo-700 px-4 md:px-6 py-4 border-b border-indigo-800 text-left hover:from-indigo-700 hover:to-indigo-800 transition-colors"
                  >
                    <h4 class="font-semibold text-white flex items-center">
                      <i class="fas fa-cogs w-5 h-5 mr-3 text-indigo-200"></i>
                      System Manager
                      <i
                        :class="[
                          'fas ml-2 transition-transform text-indigo-200',
                          openCategories.system_manager
                            ? 'fa-chevron-down'
                            : 'fa-chevron-right',
                        ]"
                      ></i>
                      <span class="ml-auto text-sm text-indigo-200"
                        >({{ getSelectedCountForCategory("system_manager") }}/{{
                          getCategoryPermissions("system_manager").length
                        }})</span
                      >
                    </h4>
                  </button>
                  <div
                    v-show="openCategories.system_manager"
                    class="p-4 md:p-6 space-y-4"
                  >
                    <!-- Main Permission -->
                    <label class="flex items-center p-3 bg-gray-50 rounded-lg">
                      <input
                        type="checkbox"
                        v-model="form.permissions.system_manager"
                        class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                      />
                      <span class="ml-3 text-sm font-medium text-gray-900"
                        >System Manager (Main Access)</span
                      >
                    </label>

                    <!-- Sub Permissions -->
                    <div class="md:ml-6 ml-3 space-y-3">
                      <h5 class="text-sm font-medium text-gray-700 mb-3">
                        System Security
                      </h5>
                      <div class="grid grid-cols-1 gap-2">
                        <label class="flex items-center break-words">
                          <input
                            type="checkbox"
                            v-model="form.permissions.define_user"
                            class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                          />
                          <span class="ml-3 text-sm text-gray-700">Define User</span>
                        </label>
                        <label class="flex items-center">
                          <input
                            type="checkbox"
                            v-model="form.permissions.amend_user_password"
                            class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                          />
                          <span class="ml-3 text-sm text-gray-700"
                            >Amend User Password</span
                          >
                        </label>
                        <label class="flex items-center">
                          <input
                            type="checkbox"
                            v-model="form.permissions.define_user_access_permission"
                            class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                          />
                          <span class="ml-3 text-sm text-gray-700"
                            >Define User Access Permission</span
                          >
                        </label>
                        <label class="flex items-center">
                          <input
                            type="checkbox"
                            v-model="form.permissions.copy_paste_user_access_permission"
                            class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                          />
                          <span class="ml-3 text-sm text-gray-700"
                            >Copy & Paste User Access Permission</span
                          >
                        </label>
                        <label class="flex items-center">
                          <input
                            type="checkbox"
                            v-model="form.permissions.reactive_unobsolete_user"
                            class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                          />
                          <span class="ml-3 text-sm text-gray-700"
                            >Reactive/Unobsolete User</span
                          >
                        </label>
                        <label class="flex items-center">
                          <input
                            type="checkbox"
                            v-model="form.permissions.view_print_user"
                            class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                          />
                          <span class="ml-3 text-sm text-gray-700"
                            >View & Print User</span
                          >
                        </label>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Sales Management -->
                <div
                  class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden"
                >
                  <button
                    type="button"
                    @click="toggleCategory('sales_management')"
                    class="sales-management-header w-full bg-gradient-to-r from-green-600 to-green-700 px-6 py-4 border-b border-green-800 text-left hover:from-green-700 hover:to-green-800 transition-colors"
                  >
                    <h4 class="font-semibold text-white flex items-center">
                      <i class="fas fa-chart-line w-5 h-5 mr-3 text-green-200"></i>
                      Sales Management
                      <i
                        :class="[
                          'fas ml-2 transition-transform text-green-200',
                          openCategories.sales_management
                            ? 'fa-chevron-down'
                            : 'fa-chevron-right',
                        ]"
                      ></i>
                      <span class="ml-auto text-sm text-green-200"
                        >({{ getSelectedCountForCategory("sales_management") }}/{{
                          getCategoryPermissions("sales_management").length
                        }})</span
                      >
                    </h4>
                  </button>
                  <div
                    v-show="openCategories.sales_management"
                    class="p-4 md:p-6 space-y-6 md:max-h-96 md:overflow-y-auto"
                  >
                    <!-- Main Permission -->
                    <label class="flex items-center p-3 bg-gray-50 rounded-lg">
                      <input
                        type="checkbox"
                        v-model="form.permissions.sales_management"
                        class="rounded border-gray-300 text-green-600 shadow-sm focus:border-green-300 focus:ring focus:ring-green-200 focus:ring-opacity-50"
                      />
                      <span class="ml-3 text-sm font-medium text-gray-900"
                        >Sales Management (Main Access)</span
                      >
                    </label>

                    <!-- Standard Requirement -->
                    <div class="border-l-4 border-green-200 pl-4">
                      <h5 class="text-sm font-medium text-gray-700 mb-3">
                        Standard Requirement
                      </h5>
                      <div
                        class="grid grid-cols-1 md:grid-cols-2 gap-2 md:max-h-64 md:overflow-y-auto"
                      >
                        <!-- Basic Define Permissions -->
                        <label class="flex items-center">
                          <input
                            type="checkbox"
                            v-model="form.permissions.define_salesperson"
                            class="rounded border-gray-300 text-green-600 shadow-sm focus:border-green-300 focus:ring focus:ring-green-200 focus:ring-opacity-50"
                          />
                          <span class="ml-3 text-xs text-gray-700"
                            >Define Salesperson</span
                          >
                        </label>
                        <label class="flex items-center">
                          <input
                            type="checkbox"
                            v-model="form.permissions.obsolete_unobsolete_salesperson"
                            class="rounded border-gray-300 text-green-600 shadow-sm focus:border-green-300 focus:ring focus:ring-green-200 focus:ring-opacity-50"
                          />
                          <span class="ml-3 text-xs text-gray-700"
                            >Obsolete/Unobsolete Salesperson</span
                          >
                        </label>
                        <label class="flex items-center">
                          <input
                            type="checkbox"
                            v-model="form.permissions.define_industry"
                            class="rounded border-gray-300 text-green-600 shadow-sm focus:border-green-300 focus:ring focus:ring-green-200 focus:ring-opacity-50"
                          />
                          <span class="ml-3 text-xs text-gray-700">Define Industry</span>
                        </label>
                        <label class="flex items-center">
                          <input
                            type="checkbox"
                            v-model="form.permissions.obsolete_unobsolete_industry"
                            class="rounded border-gray-300 text-green-600 shadow-sm focus:border-green-300 focus:ring focus:ring-green-200 focus:ring-opacity-50"
                          />
                          <span class="ml-3 text-xs text-gray-700"
                            >Obsolete/Unobsolete Industry</span
                          >
                        </label>
                        <label class="flex items-center">
                          <input
                            type="checkbox"
                            v-model="form.permissions.define_geo"
                            class="rounded border-gray-300 text-green-600 shadow-sm focus:border-green-300 focus:ring focus:ring-green-200 focus:ring-opacity-50"
                          />
                          <span class="ml-3 text-xs text-gray-700">Define Geo</span>
                        </label>
                        <label class="flex items-center">
                          <input
                            type="checkbox"
                            v-model="form.permissions.obsolete_unobsolete_geo"
                            class="rounded border-gray-300 text-green-600 shadow-sm focus:border-green-300 focus:ring focus:ring-green-200 focus:ring-opacity-50"
                          />
                          <span class="ml-3 text-xs text-gray-700"
                            >Obsolete/Unobsolete Geo</span
                          >
                        </label>
                        <label class="flex items-center">
                          <input
                            type="checkbox"
                            v-model="form.permissions.define_product_group"
                            class="rounded border-gray-300 text-green-600 shadow-sm focus:border-green-300 focus:ring focus:ring-green-200 focus:ring-opacity-50"
                          />
                          <span class="ml-3 text-xs text-gray-700"
                            >Define Product Group</span
                          >
                        </label>
                        <label class="flex items-center">
                          <input
                            type="checkbox"
                            v-model="form.permissions.obsolete_unobsolete_product_group"
                            class="rounded border-gray-300 text-green-600 shadow-sm focus:border-green-300 focus:ring focus:ring-green-200 focus:ring-opacity-50"
                          />
                          <span class="ml-3 text-xs text-gray-700"
                            >Obsolete/Unobsolete Product Group</span
                          >
                        </label>
                        <label class="flex items-center">
                          <input
                            type="checkbox"
                            v-model="form.permissions.define_product"
                            class="rounded border-gray-300 text-green-600 shadow-sm focus:border-green-300 focus:ring focus:ring-green-200 focus:ring-opacity-50"
                          />
                          <span class="ml-3 text-xs text-gray-700">Define Product</span>
                        </label>
                        <label class="flex items-center">
                          <input
                            type="checkbox"
                            v-model="form.permissions.obsolete_unobsolete_product"
                            class="rounded border-gray-300 text-green-600 shadow-sm focus:border-green-300 focus:ring focus:ring-green-200 focus:ring-opacity-50"
                          />
                          <span class="ml-3 text-xs text-gray-700"
                            >Obsolete/Unobsolete Product</span
                          >
                        </label>
                        <label class="flex items-center">
                          <input
                            type="checkbox"
                            v-model="form.permissions.define_product_design"
                            class="rounded border-gray-300 text-green-600 shadow-sm focus:border-green-300 focus:ring focus:ring-green-200 focus:ring-opacity-50"
                          />
                          <span class="ml-3 text-xs text-gray-700"
                            >Define Product Design</span
                          >
                        </label>
                        <label class="flex items-center">
                          <input
                            type="checkbox"
                            v-model="form.permissions.obsolete_unobsolete_product_design"
                            class="rounded border-gray-300 text-green-600 shadow-sm focus:border-green-300 focus:ring focus:ring-green-200 focus:ring-opacity-50"
                          />
                          <span class="ml-3 text-xs text-gray-700"
                            >Obsolete/Unobsolete Product Design</span
                          >
                        </label>
                        <label class="flex items-center">
                          <input
                            type="checkbox"
                            v-model="form.permissions.define_scoring_tool"
                            class="rounded border-gray-300 text-green-600 shadow-sm focus:border-green-300 focus:ring focus:ring-green-200 focus:ring-opacity-50"
                          />
                          <span class="ml-3 text-xs text-gray-700"
                            >Define Scoring Tool</span
                          >
                        </label>
                        <label class="flex items-center">
                          <input
                            type="checkbox"
                            v-model="form.permissions.obsolete_unobsolete_scoring_tool"
                            class="rounded border-gray-300 text-green-600 shadow-sm focus:border-green-300 focus:ring focus:ring-green-200 focus:ring-opacity-50"
                          />
                          <span class="ml-3 text-xs text-gray-700"
                            >Obsolete/Unobsolete Scoring Tool</span
                          >
                        </label>
                        <label class="flex items-center">
                          <input
                            type="checkbox"
                            v-model="form.permissions.define_paper_quality"
                            class="rounded border-gray-300 text-green-600 shadow-sm focus:border-green-300 focus:ring focus:ring-green-200 focus:ring-opacity-50"
                          />
                          <span class="ml-3 text-xs text-gray-700"
                            >Define Paper Quality</span
                          >
                        </label>
                        <label class="flex items-center">
                          <input
                            type="checkbox"
                            v-model="form.permissions.obsolete_unobsolete_paper_quality"
                            class="rounded border-gray-300 text-green-600 shadow-sm focus:border-green-300 focus:ring focus:ring-green-200 focus:ring-opacity-50"
                          />
                          <span class="ml-3 text-xs text-gray-700"
                            >Obsolete/Unobsolete Paper Quality</span
                          >
                        </label>
                        <label class="flex items-center">
                          <input
                            type="checkbox"
                            v-model="form.permissions.define_paper_flute"
                            class="rounded border-gray-300 text-green-600 shadow-sm focus:border-green-300 focus:ring focus:ring-green-200 focus:ring-opacity-50"
                          />
                          <span class="ml-3 text-xs text-gray-700"
                            >Define Paper Flute</span
                          >
                        </label>
                        <label class="flex items-center">
                          <input
                            type="checkbox"
                            v-model="form.permissions.obsolete_unobsolete_paper_flute"
                            class="rounded border-gray-300 text-green-600 shadow-sm focus:border-green-300 focus:ring focus:ring-green-200 focus:ring-opacity-50"
                          />
                          <span class="ml-3 text-xs text-gray-700"
                            >Obsolete/Unobsolete Paper Flute</span
                          >
                        </label>
                        <label class="flex items-center">
                          <input
                            type="checkbox"
                            v-model="form.permissions.define_paper_size"
                            class="rounded border-gray-300 text-green-600 shadow-sm focus:border-green-300 focus:ring focus:ring-green-200 focus:ring-opacity-50"
                          />
                          <span class="ml-3 text-xs text-gray-700"
                            >Define Paper Size</span
                          >
                        </label>
                        <label class="flex items-center">
                          <input
                            type="checkbox"
                            v-model="form.permissions.obsolete_unobsolete_paper_size"
                            class="rounded border-gray-300 text-green-600 shadow-sm focus:border-green-300 focus:ring focus:ring-green-200 focus:ring-opacity-50"
                          />
                          <span class="ml-3 text-xs text-gray-700"
                            >Obsolete/Unobsolete Paper Size</span
                          >
                        </label>
                        <label class="flex items-center">
                          <input
                            type="checkbox"
                            v-model="form.permissions.define_color_group"
                            class="rounded border-gray-300 text-green-600 shadow-sm focus:border-green-300 focus:ring focus:ring-green-200 focus:ring-opacity-50"
                          />
                          <span class="ml-3 text-xs text-gray-700"
                            >Define Color Group</span
                          >
                        </label>
                        <label class="flex items-center">
                          <input
                            type="checkbox"
                            v-model="form.permissions.obsolete_unobsolete_color_group"
                            class="rounded border-gray-300 text-green-600 shadow-sm focus:border-green-300 focus:ring focus:ring-green-200 focus:ring-opacity-50"
                          />
                          <span class="ml-3 text-xs text-gray-700"
                            >Obsolete/Unobsolete Color Group</span
                          >
                        </label>
                        <label class="flex items-center">
                          <input
                            type="checkbox"
                            v-model="form.permissions.define_color"
                            class="rounded border-gray-300 text-green-600 shadow-sm focus:border-green-300 focus:ring focus:ring-green-200 focus:ring-opacity-50"
                          />
                          <span class="ml-3 text-xs text-gray-700">Define Color</span>
                        </label>
                        <label class="flex items-center">
                          <input
                            type="checkbox"
                            v-model="form.permissions.obsolete_unobsolete_color"
                            class="rounded border-gray-300 text-green-600 shadow-sm focus:border-green-300 focus:ring focus:ring-green-200 focus:ring-opacity-50"
                          />
                          <span class="ml-3 text-xs text-gray-700"
                            >Obsolete/Unobsolete Color</span
                          >
                        </label>
                        <label class="flex items-center">
                          <input
                            type="checkbox"
                            v-model="form.permissions.define_finishing"
                            class="rounded border-gray-300 text-green-600 shadow-sm focus:border-green-300 focus:ring focus:ring-green-200 focus:ring-opacity-50"
                          />
                          <span class="ml-3 text-xs text-gray-700">Define Finishing</span>
                        </label>
                        <label class="flex items-center">
                          <input
                            type="checkbox"
                            v-model="form.permissions.obsolete_unobsolete_finishing"
                            class="rounded border-gray-300 text-green-600 shadow-sm focus:border-green-300 focus:ring focus:ring-green-200 focus:ring-opacity-50"
                          />
                          <span class="ml-3 text-xs text-gray-700"
                            >Obsolete/Unobsolete Finishing</span
                          >
                        </label>
                        <label class="flex items-center">
                          <input
                            type="checkbox"
                            v-model="form.permissions.define_stitch_wire"
                            class="rounded border-gray-300 text-green-600 shadow-sm focus:border-green-300 focus:ring focus:ring-green-200 focus:ring-opacity-50"
                          />
                          <span class="ml-3 text-xs text-gray-700"
                            >Define Stitch Wire</span
                          >
                        </label>
                        <label class="flex items-center">
                          <input
                            type="checkbox"
                            v-model="form.permissions.obsolete_unobsolete_stitch_wire"
                            class="rounded border-gray-300 text-green-600 shadow-sm focus:border-green-300 focus:ring focus:ring-green-200 focus:ring-opacity-50"
                          />
                          <span class="ml-3 text-xs text-gray-700"
                            >Obsolete/Unobsolete Stitch Wire</span
                          >
                        </label>
                        <label class="flex items-center">
                          <input
                            type="checkbox"
                            v-model="form.permissions.define_chemical_coat"
                            class="rounded border-gray-300 text-green-600 shadow-sm focus:border-green-300 focus:ring focus:ring-green-200 focus:ring-opacity-50"
                          />
                          <span class="ml-3 text-xs text-gray-700"
                            >Define Chemical Coat</span
                          >
                        </label>
                        <label class="flex items-center">
                          <input
                            type="checkbox"
                            v-model="form.permissions.obsolete_unobsolete_chemical_coat"
                            class="rounded border-gray-300 text-green-600 shadow-sm focus:border-green-300 focus:ring focus:ring-green-200 focus:ring-opacity-50"
                          />
                          <span class="ml-3 text-xs text-gray-700"
                            >Obsolete/Unobsolete Chemical Coat</span
                          >
                        </label>
                        <label class="flex items-center">
                          <input
                            type="checkbox"
                            v-model="form.permissions.define_reinforcement_tape"
                            class="rounded border-gray-300 text-green-600 shadow-sm focus:border-green-300 focus:ring focus:ring-green-200 focus:ring-opacity-50"
                          />
                          <span class="ml-3 text-xs text-gray-700"
                            >Define Reinforcement Tape</span
                          >
                        </label>
                        <label class="flex items-center">
                          <input
                            type="checkbox"
                            v-model="
                              form.permissions.obsolete_unobsolete_reinforcement_tape
                            "
                            class="rounded border-gray-300 text-green-600 shadow-sm focus:border-green-300 focus:ring focus:ring-green-200 focus:ring-opacity-50"
                          />
                          <span class="ml-3 text-xs text-gray-700"
                            >Obsolete/Unobsolete Reinforcement Tape</span
                          >
                        </label>
                        <label class="flex items-center">
                          <input
                            type="checkbox"
                            v-model="form.permissions.define_bundling_string"
                            class="rounded border-gray-300 text-green-600 shadow-sm focus:border-green-300 focus:ring focus:ring-green-200 focus:ring-opacity-50"
                          />
                          <span class="ml-3 text-xs text-gray-700"
                            >Define Bundling String</span
                          >
                        </label>
                        <label class="flex items-center">
                          <input
                            type="checkbox"
                            v-model="form.permissions.obsolete_unobsolete_bundling_string"
                            class="rounded border-gray-300 text-green-600 shadow-sm focus:border-green-300 focus:ring focus:ring-green-200 focus:ring-opacity-50"
                          />
                          <span class="ml-3 text-xs text-gray-700"
                            >Obsolete/Unobsolete Bundling String</span
                          >
                        </label>
                        <label class="flex items-center">
                          <input
                            type="checkbox"
                            v-model="form.permissions.define_wrapping_material"
                            class="rounded border-gray-300 text-green-600 shadow-sm focus:border-green-300 focus:ring focus:ring-green-200 focus:ring-opacity-50"
                          />
                          <span class="ml-3 text-xs text-gray-700"
                            >Define Wrapping Material</span
                          >
                        </label>
                        <label class="flex items-center">
                          <input
                            type="checkbox"
                            v-model="
                              form.permissions.obsolete_unobsolete_wrapping_material
                            "
                            class="rounded border-gray-300 text-green-600 shadow-sm focus:border-green-300 focus:ring focus:ring-green-200 focus:ring-opacity-50"
                          />
                          <span class="ml-3 text-xs text-gray-700"
                            >Obsolete/Unobsolete Wrapping Material</span
                          >
                        </label>
                        <label class="flex items-center">
                          <input
                            type="checkbox"
                            v-model="form.permissions.define_glueing_material"
                            class="rounded border-gray-300 text-green-600 shadow-sm focus:border-green-300 focus:ring focus:ring-green-200 focus:ring-opacity-50"
                          />
                          <span class="ml-3 text-xs text-gray-700"
                            >Define Glueing Material</span
                          >
                        </label>
                        <label class="flex items-center">
                          <input
                            type="checkbox"
                            v-model="
                              form.permissions.obsolete_unobsolete_glueing_material
                            "
                            class="rounded border-gray-300 text-green-600 shadow-sm focus:border-green-300 focus:ring focus:ring-green-200 focus:ring-opacity-50"
                          />
                          <span class="ml-3 text-xs text-gray-700"
                            >Obsolete/Unobsolete Glueing Material</span
                          >
                        </label>
                        <label class="flex items-center">
                          <input
                            type="checkbox"
                            v-model="form.permissions.define_machine"
                            class="rounded border-gray-300 text-green-600 shadow-sm focus:border-green-300 focus:ring focus:ring-green-200 focus:ring-opacity-50"
                          />
                          <span class="ml-3 text-xs text-gray-700">Define Machine</span>
                        </label>
                        <label class="flex items-center">
                          <input
                            type="checkbox"
                            v-model="form.permissions.obsolete_unobsolete_machine"
                            class="rounded border-gray-300 text-green-600 shadow-sm focus:border-green-300 focus:ring focus:ring-green-200 focus:ring-opacity-50"
                          />
                          <span class="ml-3 text-xs text-gray-700"
                            >Obsolete/Unobsolete Machine</span
                          >
                        </label>

                        <!-- View & Print Permissions -->
                        <div class="col-span-full">
                          <div class="border-t border-gray-200 pt-3 mt-3">
                            <h6 class="text-xs font-medium text-gray-600 mb-2">
                              View & Print Permissions
                            </h6>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                              <label class="flex items-center">
                                <input
                                  type="checkbox"
                                  v-model="form.permissions.view_print_salesperson"
                                  class="rounded border-gray-300 text-green-600 shadow-sm focus:border-green-300 focus:ring focus:ring-green-200 focus:ring-opacity-50"
                                />
                                <span class="ml-3 text-xs text-gray-600"
                                  >View & Print Salesperson</span
                                >
                              </label>
                              <label class="flex items-center">
                                <input
                                  type="checkbox"
                                  v-model="form.permissions.view_print_industry"
                                  class="rounded border-gray-300 text-green-600 shadow-sm focus:border-green-300 focus:ring focus:ring-green-200 focus:ring-opacity-50"
                                />
                                <span class="ml-3 text-xs text-gray-600"
                                  >View & Print Industry</span
                                >
                              </label>
                              <label class="flex items-center">
                                <input
                                  type="checkbox"
                                  v-model="form.permissions.view_print_geo"
                                  class="rounded border-gray-300 text-green-600 shadow-sm focus:border-green-300 focus:ring focus:ring-green-200 focus:ring-opacity-50"
                                />
                                <span class="ml-3 text-xs text-gray-600"
                                  >View & Print Geo</span
                                >
                              </label>
                              <label class="flex items-center">
                                <input
                                  type="checkbox"
                                  v-model="form.permissions.view_print_product_group"
                                  class="rounded border-gray-300 text-green-600 shadow-sm focus:border-green-300 focus:ring focus:ring-green-200 focus:ring-opacity-50"
                                />
                                <span class="ml-3 text-xs text-gray-600"
                                  >View & Print Product Group</span
                                >
                              </label>
                              <label class="flex items-center">
                                <input
                                  type="checkbox"
                                  v-model="form.permissions.view_print_product"
                                  class="rounded border-gray-300 text-green-600 shadow-sm focus:border-green-300 focus:ring focus:ring-green-200 focus:ring-opacity-50"
                                />
                                <span class="ml-3 text-xs text-gray-600"
                                  >View & Print Product</span
                                >
                              </label>
                              <label class="flex items-center">
                                <input
                                  type="checkbox"
                                  v-model="form.permissions.view_print_product_design"
                                  class="rounded border-gray-300 text-green-600 shadow-sm focus:border-green-300 focus:ring focus:ring-green-200 focus:ring-opacity-50"
                                />
                                <span class="ml-3 text-xs text-gray-600"
                                  >View & Print Product Design</span
                                >
                              </label>
                              <label class="flex items-center">
                                <input
                                  type="checkbox"
                                  v-model="form.permissions.view_print_paper_quality"
                                  class="rounded border-gray-300 text-green-600 shadow-sm focus:border-green-300 focus:ring focus:ring-green-200 focus:ring-opacity-50"
                                />
                                <span class="ml-3 text-xs text-gray-600"
                                  >View & Print Paper Quality</span
                                >
                              </label>
                              <label class="flex items-center">
                                <input
                                  type="checkbox"
                                  v-model="form.permissions.view_print_paper_flute"
                                  class="rounded border-gray-300 text-green-600 shadow-sm focus:border-green-300 focus:ring focus:ring-green-200 focus:ring-opacity-50"
                                />
                                <span class="ml-3 text-xs text-gray-600"
                                  >View & Print Paper Flute</span
                                >
                              </label>
                              <label class="flex items-center">
                                <input
                                  type="checkbox"
                                  v-model="form.permissions.view_print_paper_size"
                                  class="rounded border-gray-300 text-green-600 shadow-sm focus:border-green-300 focus:ring focus:ring-green-200 focus:ring-opacity-50"
                                />
                                <span class="ml-3 text-xs text-gray-600"
                                  >View & Print Paper Size</span
                                >
                              </label>
                              <label class="flex items-center">
                                <input
                                  type="checkbox"
                                  v-model="form.permissions.view_print_scoring_tool"
                                  class="rounded border-gray-300 text-green-600 shadow-sm focus:border-green-300 focus:ring focus:ring-green-200 focus:ring-opacity-50"
                                />
                                <span class="ml-3 text-xs text-gray-600"
                                  >View & Print Scoring Tool</span
                                >
                              </label>
                              <label class="flex items-center">
                                <input
                                  type="checkbox"
                                  v-model="form.permissions.view_print_color_group"
                                  class="rounded border-gray-300 text-green-600 shadow-sm focus:border-green-300 focus:ring focus:ring-green-200 focus:ring-opacity-50"
                                />
                                <span class="ml-3 text-xs text-gray-600"
                                  >View & Print Color Group</span
                                >
                              </label>
                              <label class="flex items-center">
                                <input
                                  type="checkbox"
                                  v-model="form.permissions.view_print_color"
                                  class="rounded border-gray-300 text-green-600 shadow-sm focus:border-green-300 focus:ring focus:ring-green-200 focus:ring-opacity-50"
                                />
                                <span class="ml-3 text-xs text-gray-600"
                                  >View & Print Color</span
                                >
                              </label>
                              <label class="flex items-center">
                                <input
                                  type="checkbox"
                                  v-model="form.permissions.view_print_finishing"
                                  class="rounded border-gray-300 text-green-600 shadow-sm focus:border-green-300 focus:ring focus:ring-green-200 focus:ring-opacity-50"
                                />
                                <span class="ml-3 text-xs text-gray-600"
                                  >View & Print Finishing</span
                                >
                              </label>
                              <label class="flex items-center">
                                <input
                                  type="checkbox"
                                  v-model="form.permissions.view_print_stitch_wire"
                                  class="rounded border-gray-300 text-green-600 shadow-sm focus:border-green-300 focus:ring focus:ring-green-200 focus:ring-opacity-50"
                                />
                                <span class="ml-3 text-xs text-gray-600"
                                  >View & Print Stitch Wire</span
                                >
                              </label>
                              <label class="flex items-center">
                                <input
                                  type="checkbox"
                                  v-model="form.permissions.view_print_chemical_coat"
                                  class="rounded border-gray-300 text-green-600 shadow-sm focus:border-green-300 focus:ring focus:ring-green-200 focus:ring-opacity-50"
                                />
                                <span class="ml-3 text-xs text-gray-600"
                                  >View & Print Chemical Coat</span
                                >
                              </label>
                              <label class="flex items-center">
                                <input
                                  type="checkbox"
                                  v-model="form.permissions.view_print_reinforcement_tape"
                                  class="rounded border-gray-300 text-green-600 shadow-sm focus:border-green-300 focus:ring focus:ring-green-200 focus:ring-opacity-50"
                                />
                                <span class="ml-3 text-xs text-gray-600"
                                  >View & Print Reinforcement Tape</span
                                >
                              </label>
                              <label class="flex items-center">
                                <input
                                  type="checkbox"
                                  v-model="form.permissions.view_print_bundling_string"
                                  class="rounded border-gray-300 text-green-600 shadow-sm focus:border-green-300 focus:ring focus:ring-green-200 focus:ring-opacity-50"
                                />
                                <span class="ml-3 text-xs text-gray-600"
                                  >View & Print Bundling String</span
                                >
                              </label>
                              <label class="flex items-center">
                                <input
                                  type="checkbox"
                                  v-model="form.permissions.view_print_wrapping_material"
                                  class="rounded border-gray-300 text-green-600 shadow-sm focus:border-green-300 focus:ring focus:ring-green-200 focus:ring-opacity-50"
                                />
                                <span class="ml-3 text-xs text-gray-600"
                                  >View & Print Wrapping Material</span
                                >
                              </label>
                              <label class="flex items-center">
                                <input
                                  type="checkbox"
                                  v-model="form.permissions.view_print_glueing_material"
                                  class="rounded border-gray-300 text-green-600 shadow-sm focus:border-green-300 focus:ring focus:ring-green-200 focus:ring-opacity-50"
                                />
                                <span class="ml-3 text-xs text-gray-600"
                                  >View & Print Glueing Material</span
                                >
                              </label>
                              <label class="flex items-center">
                                <input
                                  type="checkbox"
                                  v-model="form.permissions.view_print_machine"
                                  class="rounded border-gray-300 text-green-600 shadow-sm focus:border-green-300 focus:ring focus:ring-green-200 focus:ring-opacity-50"
                                />
                                <span class="ml-3 text-xs text-gray-600"
                                  >View & Print Machine</span
                                >
                              </label>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>

                    <!-- Customer Account -->
                    <div class="border-l-4 border-green-200 pl-4">
                      <h5 class="text-sm font-medium text-gray-700 mb-3">
                        Customer Account
                      </h5>
                      <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                        <label class="flex items-center">
                          <input
                            type="checkbox"
                            v-model="form.permissions.define_customer_group"
                            class="rounded border-gray-300 text-green-600 shadow-sm focus:border-green-300 focus:ring focus:ring-green-200 focus:ring-opacity-50"
                          />
                          <span class="ml-3 text-xs text-gray-700"
                            >Define Customer Group</span
                          >
                        </label>
                        <label class="flex items-center">
                          <input
                            type="checkbox"
                            v-model="form.permissions.obsolete_reactive_customer_group"
                            class="rounded border-gray-300 text-green-600 shadow-sm focus:border-green-300 focus:ring focus:ring-green-200 focus:ring-opacity-50"
                          />
                          <span class="ml-3 text-xs text-gray-700"
                            >Obsolete/Reactive Customer Group</span
                          >
                        </label>
                        <label class="flex items-center">
                          <input
                            type="checkbox"
                            v-model="form.permissions.update_customer_account"
                            class="rounded border-gray-300 text-green-600 shadow-sm focus:border-green-300 focus:ring focus:ring-green-200 focus:ring-opacity-50"
                          />
                          <span class="ml-3 text-xs text-gray-700"
                            >Update Customer Account</span
                          >
                        </label>
                        <label class="flex items-center">
                          <input
                            type="checkbox"
                            v-model="form.permissions.obsolete_reactive_customer_ac"
                            class="rounded border-gray-300 text-green-600 shadow-sm focus:border-green-300 focus:ring focus:ring-green-200 focus:ring-opacity-50"
                          />
                          <span class="ml-3 text-xs text-gray-700"
                            >Obsolete/Reactive Customer A/C</span
                          >
                        </label>
                        <label class="flex items-center">
                          <input
                            type="checkbox"
                            v-model="form.permissions.define_customer_alternate_address"
                            class="rounded border-gray-300 text-green-600 shadow-sm focus:border-green-300 focus:ring focus:ring-green-200 focus:ring-opacity-50"
                          />
                          <span class="ml-3 text-xs text-gray-700"
                            >Define Customer Alternate Address</span
                          >
                        </label>
                      </div>

                      <!-- Customer View & Print -->
                      <div class="border-t border-gray-200 pt-3 mt-3">
                        <h6 class="text-xs font-medium text-gray-600 mb-2">
                          Customer View & Print
                        </h6>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                          <label class="flex items-center">
                            <input
                              type="checkbox"
                              v-model="form.permissions.view_print_customer_group"
                              class="rounded border-gray-300 text-green-600 shadow-sm focus:border-green-300 focus:ring focus:ring-green-200 focus:ring-opacity-50"
                            />
                            <span class="ml-3 text-xs text-gray-600"
                              >View & Print Customer Group</span
                            >
                          </label>
                          <label class="flex items-center">
                            <input
                              type="checkbox"
                              v-model="form.permissions.view_print_customer_account"
                              class="rounded border-gray-300 text-green-600 shadow-sm focus:border-green-300 focus:ring focus:ring-green-200 focus:ring-opacity-50"
                            />
                            <span class="ml-3 text-xs text-gray-600"
                              >View & Print Customer Account</span
                            >
                          </label>
                          <label class="flex items-center">
                            <input
                              type="checkbox"
                              v-model="
                                form.permissions.view_print_customer_alternate_address
                              "
                              class="rounded border-gray-300 text-green-600 shadow-sm focus:border-green-300 focus:ring focus:ring-green-200 focus:ring-opacity-50"
                            />
                            <span class="ml-3 text-xs text-gray-600"
                              >View & Print Customer Alt. Address</span
                            >
                          </label>
                        </div>
                      </div>
                    </div>

                    <!-- Master Card -->
                    <div class="border-l-0 md:border-l-4 border-green-200 pl-3 md:pl-4">
                      <h5 class="text-sm font-medium text-gray-700 mb-3">Master Card</h5>
                      <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                        <label class="flex items-center">
                          <input
                            type="checkbox"
                            v-model="form.permissions.update_mc"
                            class="rounded border-gray-300 text-green-600 shadow-sm focus:border-green-300 focus:ring focus:ring-green-200 focus:ring-opacity-50"
                          />
                          <span class="ml-3 text-xs text-gray-700">Update MC</span>
                        </label>
                        <label class="flex items-center">
                          <input
                            type="checkbox"
                            v-model="form.permissions.obsolete_reactive_mc"
                            class="rounded border-gray-300 text-green-600 shadow-sm focus:border-green-300 focus:ring focus:ring-green-200 focus:ring-opacity-50"
                          />
                          <span class="ml-3 text-xs text-gray-700"
                            >Obsolete & Reactive MC</span
                          >
                        </label>
                        <label class="flex items-center">
                          <input
                            type="checkbox"
                            v-model="form.permissions.view_print_mc"
                            class="rounded border-gray-300 text-green-600 shadow-sm focus:border-green-300 focus:ring focus:ring-green-200 focus:ring-opacity-50"
                          />
                          <span class="ml-3 text-xs text-gray-700">View & Print MC</span>
                        </label>
                      </div>
                    </div>

                    <!-- Sales Order -->
                    <div class="border-l-0 md:border-l-4 border-green-200 pl-3 md:pl-4">
                      <h5 class="text-sm font-medium text-gray-700 mb-3">Sales Order</h5>
                      <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                        <label class="flex items-center">
                          <input
                            type="checkbox"
                            v-model="form.permissions.prepare_mc_so"
                            class="rounded border-gray-300 text-green-600 shadow-sm focus:border-green-300 focus:ring focus:ring-green-200 focus:ring-opacity-50"
                          />
                          <span class="ml-3 text-xs text-gray-700">Prepare MC SO</span>
                        </label>
                        <label class="flex items-center">
                          <input
                            type="checkbox"
                            v-model="form.permissions.print_so"
                            class="rounded border-gray-300 text-green-600 shadow-sm focus:border-green-300 focus:ring focus:ring-green-200 focus:ring-opacity-50"
                          />
                          <span class="ml-3 text-xs text-gray-700">Print SO</span>
                        </label>
                        <label class="flex items-center">
                          <input
                            type="checkbox"
                            v-model="form.permissions.cancel_so"
                            class="rounded border-gray-300 text-green-600 shadow-sm focus:border-green-300 focus:ring focus:ring-green-200 focus:ring-opacity-50"
                          />
                          <span class="ml-3 text-xs text-gray-700">Cancel SO</span>
                        </label>
                        <label class="flex items-center">
                          <input
                            type="checkbox"
                            v-model="form.permissions.amend_so"
                            class="rounded border-gray-300 text-green-600 shadow-sm focus:border-green-300 focus:ring focus:ring-green-200 focus:ring-opacity-50"
                          />
                          <span class="ml-3 text-xs text-gray-700">Amend SO</span>
                        </label>
                      </div>
                    </div>

                    <!-- Customer Service -->
                    <div class="border-l-0 md:border-l-4 border-green-200 pl-3 md:pl-4">
                      <h5 class="text-sm font-medium text-gray-700 mb-3">
                        Customer Service
                      </h5>
                      <label class="flex items-center p-3 bg-gray-50 rounded-lg">
                        <input
                          type="checkbox"
                          v-model="form.permissions.customer_service_dashboard"
                          class="rounded border-gray-300 text-green-600 shadow-sm focus:border-green-300 focus:ring focus:ring-green-200 focus:ring-opacity-50"
                        />
                        <span class="ml-3 text-sm font-medium text-gray-900"
                          >Customer Service Dashboard</span
                        >
                      </label>
                    </div>

                    <!-- Quick Actions for Sales -->
                    <div class="flex flex-wrap gap-2 pt-4 border-t border-gray-200">
                      <button
                        @click="selectAllSalesPermissions"
                        class="px-3 py-1 text-xs bg-green-100 text-green-700 rounded-md hover:bg-green-200 transition-colors"
                      >
                        Select All Sales
                      </button>
                      <button
                        @click="clearAllSalesPermissions"
                        class="px-3 py-1 text-xs bg-red-100 text-red-700 rounded-md hover:bg-red-200 transition-colors"
                      >
                        Clear All Sales
                      </button>
                    </div>
                  </div>
                </div>

                <!-- Warehouse Management -->
                <div
                  class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden"
                >
                  <button
                    type="button"
                    @click="toggleCategory('warehouse_management')"
                    class="warehouse-management-header w-full bg-gradient-to-r from-yellow-600 to-yellow-700 px-4 md:px-6 py-4 border-b border-yellow-800 text-left hover:from-yellow-700 hover:to-yellow-800 transition-colors"
                  >
                    <h4 class="font-semibold text-white flex items-center">
                      <i class="fas fa-warehouse w-5 h-5 mr-3 text-yellow-200"></i>
                      Warehouse Management
                      <i
                        :class="[
                          'fas ml-2 transition-transform text-yellow-200',
                          openCategories.warehouse_management
                            ? 'fa-chevron-down'
                            : 'fa-chevron-right',
                        ]"
                      ></i>
                      <span class="ml-auto text-sm text-yellow-200"
                        >({{ getSelectedCountForCategory("warehouse_management") }}/{{
                          getCategoryPermissions("warehouse_management").length
                        }})</span
                      >
                    </h4>
                  </button>
                  <div
                    v-show="openCategories.warehouse_management"
                    class="p-4 md:p-6 space-y-4 md:max-h-96 md:overflow-y-auto"
                  >
                    <!-- Main Permission -->
                    <label class="flex items-center p-3 bg-gray-50 rounded-lg">
                      <input
                        type="checkbox"
                        v-model="form.permissions.warehouse_management"
                        class="rounded border-gray-300 text-yellow-600 shadow-sm focus:border-yellow-300 focus:ring focus:ring-yellow-200 focus:ring-opacity-50"
                      />
                      <span class="ml-3 text-sm font-medium text-gray-900"
                        >Warehouse Management (Main Access)</span
                      >
                    </label>

                    <!-- Warehouse Sub Permissions -->
                    <div class="space-y-6">
                      <!-- Delivery Order Section -->
                      <div
                        class="border-l-0 md:border-l-4 border-yellow-200 pl-3 md:pl-4"
                      >
                        <h5 class="text-sm font-medium text-gray-700 mb-3">
                          Delivery Order
                        </h5>

                        <div class="space-y-4">
                          <!-- DO Setup -->
                          <div class="pl-2">
                            <h6
                              class="text-xs font-semibold text-gray-600 mb-2 uppercase tracking-wider"
                            >
                              Setup
                            </h6>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                              <label class="flex items-center">
                                <input
                                  type="checkbox"
                                  v-model="form.permissions.define_vehicle_class"
                                  class="rounded border-gray-300 text-yellow-600 shadow-sm focus:border-yellow-300 focus:ring focus:ring-yellow-200 focus:ring-opacity-50"
                                />
                                <span class="ml-3 text-xs text-gray-700"
                                  >Define Vehicle Class</span
                                >
                              </label>
                              <label class="flex items-center">
                                <input
                                  type="checkbox"
                                  v-model="form.permissions.obsolete_unobsolete_vehicle_class"
                                  class="rounded border-gray-300 text-yellow-600 shadow-sm focus:border-yellow-300 focus:ring focus:ring-yellow-200 focus:ring-opacity-50"
                                />
                                <span class="ml-3 text-xs text-gray-700"
                                  >Obsolete/Unobsolete Vehicle Class</span
                                >
                              </label>
                              <label class="flex items-center">
                                <input
                                  type="checkbox"
                                  v-model="form.permissions.define_vehicle"
                                  class="rounded border-gray-300 text-yellow-600 shadow-sm focus:border-yellow-300 focus:ring focus:ring-yellow-200 focus:ring-opacity-50"
                                />
                                <span class="ml-3 text-xs text-gray-700"
                                  >Define Vehicle</span
                                >
                              </label>
                              <label class="flex items-center">
                                <input
                                  type="checkbox"
                                  v-model="form.permissions.obsolete_unobsolete_vehicle"
                                  class="rounded border-gray-300 text-yellow-600 shadow-sm focus:border-yellow-300 focus:ring focus:ring-yellow-200 focus:ring-opacity-50"
                                />
                                <span class="ml-3 text-xs text-gray-700"
                                  >Obsolete/Unobsolete Vehicle</span
                                >
                              </label>
                              <label class="flex items-center">
                                <input
                                  type="checkbox"
                                  v-model="form.permissions.view_print_vehicle"
                                  class="rounded border-gray-300 text-yellow-600 shadow-sm focus:border-yellow-300 focus:ring focus:ring-yellow-200 focus:ring-opacity-50"
                                />
                                <span class="ml-3 text-xs text-gray-700"
                                  >View & Print Vehicle</span
                                >
                              </label>
                              <label class="flex items-center">
                                <input
                                  type="checkbox"
                                  v-model="form.permissions.view_print_vehicle_class"
                                  class="rounded border-gray-300 text-yellow-600 shadow-sm focus:border-yellow-300 focus:ring focus:ring-yellow-200 focus:ring-opacity-50"
                                />
                                <span class="ml-3 text-xs text-gray-700"
                                  >View & Print Vehicle Class</span
                                >
                              </label>
                            </div>
                          </div>

                          <!-- DO Processing -->
                          <div class="pl-2 border-t border-gray-100 pt-2">
                            <h6
                              class="text-xs font-semibold text-gray-600 mb-2 uppercase tracking-wider"
                            >
                              DO Processing
                            </h6>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                              <label class="flex items-center">
                                <input
                                  type="checkbox"
                                  v-model="
                                    form.permissions.prepare_delivery_order_multiple
                                  "
                                  class="rounded border-gray-300 text-yellow-600 shadow-sm focus:border-yellow-300 focus:ring focus:ring-yellow-200 focus:ring-opacity-50"
                                />
                                <span class="ml-3 text-xs text-gray-700"
                                  >Prepare DO (Multiple Item)</span
                                >
                              </label>
                              <label class="flex items-center">
                                <input
                                  type="checkbox"
                                  v-model="form.permissions.print_delivery_order"
                                  class="rounded border-gray-300 text-yellow-600 shadow-sm focus:border-yellow-300 focus:ring focus:ring-yellow-200 focus:ring-opacity-50"
                                />
                                <span class="ml-3 text-xs text-gray-700"
                                  >Print Delivery Order</span
                                >
                              </label>
                              <label class="flex items-center">
                                <input
                                  type="checkbox"
                                  v-model="form.permissions.amend_delivery_order"
                                  class="rounded border-gray-300 text-yellow-600 shadow-sm focus:border-yellow-300 focus:ring focus:ring-yellow-200 focus:ring-opacity-50"
                                />
                                <span class="ml-3 text-xs text-gray-700"
                                  >Amend Delivery Order</span
                                >
                              </label>
                              <label class="flex items-center">
                                <input
                                  type="checkbox"
                                  v-model="form.permissions.cancel_delivery_order"
                                  class="rounded border-gray-300 text-yellow-600 shadow-sm focus:border-yellow-300 focus:ring focus:ring-yellow-200 focus:ring-opacity-50"
                                />
                                <span class="ml-3 text-xs text-gray-700"
                                  >Cancel Delivery Order</span
                                >
                              </label>
                            </div>
                          </div>
                        </div>
                      </div>

                      <!-- Invoice Section -->
                      <div
                        class="border-l-0 md:border-l-4 border-yellow-200 pl-3 md:pl-4"
                      >
                        <h5 class="text-sm font-medium text-gray-700 mb-3">Invoice</h5>

                        <div class="space-y-4">
                          <!-- Invoice Setup -->
                          <div class="pl-2">
                            <h6
                              class="text-xs font-semibold text-gray-600 mb-2 uppercase tracking-wider"
                            >
                              Setup
                            </h6>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                              <label class="flex items-center">
                                <input
                                  type="checkbox"
                                  v-model="form.permissions.define_tax_type"
                                  class="rounded border-gray-300 text-yellow-600 shadow-sm focus:border-yellow-300 focus:ring focus:ring-yellow-200 focus:ring-opacity-50"
                                />
                                <span class="ml-3 text-xs text-gray-700"
                                  >Define Tax Type</span
                                >
                              </label>
                              <label class="flex items-center">
                                <input
                                  type="checkbox"
                                  v-model="form.permissions.obsolete_unobsolete_tax_type"
                                  class="rounded border-gray-300 text-yellow-600 shadow-sm focus:border-yellow-300 focus:ring focus:ring-yellow-200 focus:ring-opacity-50"
                                />
                                <span class="ml-3 text-xs text-gray-700"
                                  >Obsolete/Unobsolete Tax Type</span
                                >
                              </label>
                              <label class="flex items-center">
                                <input
                                  type="checkbox"
                                  v-model="form.permissions.define_tax_group"
                                  class="rounded border-gray-300 text-yellow-600 shadow-sm focus:border-yellow-300 focus:ring focus:ring-yellow-200 focus:ring-opacity-50"
                                />
                                <span class="ml-3 text-xs text-gray-700"
                                  >Define Tax Group</span
                                >
                              </label>
                              <label class="flex items-center">
                                <input
                                  type="checkbox"
                                  v-model="form.permissions.obsolete_unobsolete_tax_group"
                                  class="rounded border-gray-300 text-yellow-600 shadow-sm focus:border-yellow-300 focus:ring focus:ring-yellow-200 focus:ring-opacity-50"
                                />
                                <span class="ml-3 text-xs text-gray-700"
                                  >Obsolete/Unobsolete Tax Group</span
                                >
                              </label>
                              <label class="flex items-center">
                                <input
                                  type="checkbox"
                                  v-model="
                                    form.permissions.define_customer_sales_tax_index
                                  "
                                  class="rounded border-gray-300 text-yellow-600 shadow-sm focus:border-yellow-300 focus:ring focus:ring-yellow-200 focus:ring-opacity-50"
                                />
                                <span class="ml-3 text-xs text-gray-700"
                                  >Define Customer Sales Tax Index</span
                                >
                              </label>
                              <label class="flex items-center">
                                <input
                                  type="checkbox"
                                  v-model="
                                    form.permissions
                                      .obsolete_unobsolete_customer_sales_tax_index
                                  "
                                  class="rounded border-gray-300 text-yellow-600 shadow-sm focus:border-yellow-300 focus:ring focus:ring-yellow-200 focus:ring-opacity-50"
                                />
                                <span class="ml-3 text-xs text-gray-700"
                                  >Obsolete/Unobsolete Customer Sales Tax Index</span
                                >
                              </label>
                              <label class="flex items-center">
                                <input
                                  type="checkbox"
                                  v-model="form.permissions.view_print_tax_type"
                                  class="rounded border-gray-300 text-yellow-600 shadow-sm focus:border-yellow-300 focus:ring focus:ring-yellow-200 focus:ring-opacity-50"
                                />
                                <span class="ml-3 text-xs text-gray-700"
                                  >View & Print Tax Type</span
                                >
                              </label>
                              <label class="flex items-center">
                                <input
                                  type="checkbox"
                                  v-model="form.permissions.view_print_tax_group"
                                  class="rounded border-gray-300 text-yellow-600 shadow-sm focus:border-yellow-300 focus:ring focus:ring-yellow-200 focus:ring-opacity-50"
                                />
                                <span class="ml-3 text-xs text-gray-700"
                                  >View & Print Tax Group</span
                                >
                              </label>
                              <label class="flex items-center">
                                <input
                                  type="checkbox"
                                  v-model="
                                    form.permissions.view_print_customer_sales_tax_index
                                  "
                                  class="rounded border-gray-300 text-yellow-600 shadow-sm focus:border-yellow-300 focus:ring focus:ring-yellow-200 focus:ring-opacity-50"
                                />
                                <span class="ml-3 text-xs text-gray-700"
                                  >View & Print Customer Sales Tax Index</span
                                >
                              </label>
                            </div>
                          </div>

                          <!-- IV Processing -->
                          <div class="pl-2 border-t border-gray-100 pt-2">
                            <h6
                              class="text-xs font-semibold text-gray-600 mb-2 uppercase tracking-wider"
                            >
                              IV Processing
                            </h6>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                              <label class="flex items-center">
                                <input
                                  type="checkbox"
                                  v-model="
                                    form.permissions.prepare_invoice_by_do_current_period
                                  "
                                  class="rounded border-gray-300 text-yellow-600 shadow-sm focus:border-yellow-300 focus:ring focus:ring-yellow-200 focus:ring-opacity-50"
                                />
                                <span class="ml-3 text-xs text-gray-700"
                                  >Prepare Invoice by D/Order (Current Period)</span
                                >
                              </label>
                              <label class="flex items-center">
                                <input
                                  type="checkbox"
                                  v-model="
                                    form.permissions.prepare_invoice_by_do_open_period
                                  "
                                  class="rounded border-gray-300 text-yellow-600 shadow-sm focus:border-yellow-300 focus:ring focus:ring-yellow-200 focus:ring-opacity-50"
                                />
                                <span class="ml-3 text-xs text-gray-700"
                                  >Prepare Invoice by D/Order (Open Period)</span
                                >
                              </label>
                              <label class="flex items-center">
                                <input
                                  type="checkbox"
                                  v-model="form.permissions.print_invoice"
                                  class="rounded border-gray-300 text-yellow-600 shadow-sm focus:border-yellow-300 focus:ring focus:ring-yellow-200 focus:ring-opacity-50"
                                />
                                <span class="ml-3 text-xs text-gray-700"
                                  >Print Invoice</span
                                >
                              </label>
                              <label class="flex items-center">
                                <input
                                  type="checkbox"
                                  v-model="form.permissions.amend_invoice"
                                  class="rounded border-gray-300 text-yellow-600 shadow-sm focus:border-yellow-300 focus:ring focus:ring-yellow-200 focus:ring-opacity-50"
                                />
                                <span class="ml-3 text-xs text-gray-700"
                                  >Amend Invoice</span
                                >
                              </label>
                              <label class="flex items-center">
                                <input
                                  type="checkbox"
                                  v-model="form.permissions.cancel_active_invoice"
                                  class="rounded border-gray-300 text-yellow-600 shadow-sm focus:border-yellow-300 focus:ring focus:ring-yellow-200 focus:ring-opacity-50"
                                />
                                <span class="ml-3 text-xs text-gray-700"
                                  >Cancel Active Invoice</span
                                >
                              </label>
                              <label class="flex items-center">
                                <input
                                  type="checkbox"
                                  v-model="form.permissions.view_print_invoice_log"
                                  class="rounded border-gray-300 text-yellow-600 shadow-sm focus:border-yellow-300 focus:ring focus:ring-yellow-200 focus:ring-opacity-50"
                                />
                                <span class="ml-3 text-xs text-gray-700"
                                  >View & Print Invoice Log</span
                                >
                              </label>
                            </div>
                          </div>

                          <!-- Tax DJP -->
                          <div class="pl-2 border-t border-gray-100 pt-2">
                            <h6
                              class="text-xs font-semibold text-gray-600 mb-2 uppercase tracking-wider"
                            >
                              Tax DJP
                            </h6>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                              <label class="flex items-center">
                                <input
                                  type="checkbox"
                                  v-model="form.permissions.input_no_faktur"
                                  class="rounded border-gray-300 text-yellow-600 shadow-sm focus:border-yellow-300 focus:ring focus:ring-yellow-200 focus:ring-opacity-50"
                                />
                                <span class="ml-3 text-xs text-gray-700"
                                  >Input No Faktur</span
                                >
                              </label>
                              <label class="flex items-center">
                                <input
                                  type="checkbox"
                                  v-model="form.permissions.export_to_coretax"
                                  class="rounded border-gray-300 text-yellow-600 shadow-sm focus:border-yellow-300 focus:ring focus:ring-yellow-200 focus:ring-opacity-50"
                                />
                                <span class="ml-3 text-xs text-gray-700"
                                  >Export to Coretax</span
                                >
                              </label>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>

                    <!-- Quick Actions for Warehouse -->
                    <div class="flex flex-wrap gap-2 pt-4 border-t border-gray-200">
                      <button
                        @click="selectAllWarehousePermissions"
                        class="px-3 py-1 text-xs bg-yellow-100 text-yellow-700 rounded-md hover:bg-yellow-200 transition-colors"
                      >
                        Select All Warehouse
                      </button>
                      <button
                        @click="clearAllWarehousePermissions"
                        class="px-3 py-1 text-xs bg-red-100 text-red-700 rounded-md hover:bg-red-200 transition-colors"
                      >
                        Clear All Warehouse
                      </button>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Submit Button -->
              <div
                class="flex flex-col sm:flex-row gap-4 pt-6 border-t border-gray-200 justify-end"
              >
                <Link
                  href="/user"
                  class="w-full sm:w-auto inline-flex justify-center items-center px-6 py-3 border border-gray-300 shadow-sm text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors"
                >
                  <XIcon class="h-5 w-5 mr-2" />
                  Back
                </Link>
                <button
                  type="submit"
                  :disabled="isSaving"
                  class="w-full sm:w-auto inline-flex justify-center items-center px-6 py-3 border border-transparent shadow-sm text-sm font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 disabled:bg-blue-400 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors"
                >
                  <SaveIcon class="h-5 w-5 mr-2" />
                  {{ isSaving ? "Saving..." : "Save Permissions" }}
                </button>
              </div>
            </form>
          </div>
        </div>

        <!-- Flash Messages -->
        <div
          v-if="$page.props.flash.success"
          class="mt-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg"
        >
          <i class="fas fa-check-circle mr-2"></i>{{ $page.props.flash.success }}
        </div>

        <div
          v-if="$page.props.flash.error"
          class="mt-6 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-lg"
        >
          <i class="fas fa-exclamation-circle mr-2"></i>{{ $page.props.flash.error }}
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script>
import { Head, Link } from "@inertiajs/vue3";
import AppLayout from "@/Layouts/AppLayout.vue";
import UserModal from "@/Components/user-modal.vue";
import { Switch, SwitchGroup, SwitchLabel } from "@headlessui/vue";
import Swal from "sweetalert2";
import {
  UserIcon,
  ShieldCheckIcon,
  MagnifyingGlassIcon as SearchIcon,
  ArrowDownTrayIcon as SaveIcon,
  XMarkIcon as XIcon,
  CheckCircleIcon,
  ExclamationCircleIcon,
  IdentificationIcon,
} from "@heroicons/vue/24/outline";

export default {
  components: {
    AppLayout,
    Head,
    Link,
    UserModal,
    Switch,
    SwitchGroup,
    SwitchLabel,
    UserIcon,
    ShieldCheckIcon,
    SearchIcon,
    SaveIcon,
    XIcon,
    CheckCircleIcon,
    ExclamationCircleIcon,
    IdentificationIcon,
  },
  data() {
    return {
      searchForm: {
        user_id: "",
      },
      foundUser: null,
      isSearching: false,
      isSaving: false,
      searchMessage: null,
      searchMessageClass: "",
      searchMessageIcon: null,
      searchMessageType: "info",
      selectAllPermissions: false,
      openCategories: {
        system_manager: false,
        sales_management: false,
        warehouse_management: false,
      },
      showUserModal: false,
      isLoadingUsers: false,
      users: [],
      showAllStandardRequirement: false,
      isLoadingPermissions: false,
      form: {
        permissions: {
          dashboard: false,
          system_manager: false,
          define_user: false,
          amend_user_password: false,
          define_user_access_permission: false,
          copy_paste_user_access_permission: false,
          reactive_unobsolete_user: false,
          view_print_user: false,
          sales_management: false,
          // Standard Requirement (sync dengan sidebar)
          define_salesperson: false,
          obsolete_unobsolete_salesperson: false,
          define_industry: false,
          obsolete_unobsolete_industry: false,
          define_geo: false,
          obsolete_unobsolete_geo: false,
          define_product_group: false,
          obsolete_unobsolete_product_group: false,
          define_product: false,
          obsolete_unobsolete_product: false,
          define_product_design: false,
          obsolete_unobsolete_product_design: false,
          define_scoring_tool: false,
          obsolete_unobsolete_scoring_tool: false,
          define_paper_quality: false,
          obsolete_unobsolete_paper_quality: false,
          define_paper_flute: false,
          obsolete_unobsolete_paper_flute: false,
          define_paper_size: false,
          obsolete_unobsolete_paper_size: false,
          define_color_group: false,
          obsolete_unobsolete_color_group: false,
          define_color: false,
          obsolete_unobsolete_color: false,
          define_finishing: false,
          obsolete_unobsolete_finishing: false,
          define_stitch_wire: false,
          obsolete_unobsolete_stitch_wire: false,
          define_chemical_coat: false,
          obsolete_unobsolete_chemical_coat: false,
          define_reinforcement_tape: false,
          obsolete_unobsolete_reinforcement_tape: false,
          define_bundling_string: false,
          obsolete_unobsolete_bundling_string: false,
          define_wrapping_material: false,
          obsolete_unobsolete_wrapping_material: false,
          define_glueing_material: false,
          obsolete_unobsolete_glueing_material: false,
          define_machine: false,
          obsolete_unobsolete_machine: false,

          // View & Print Standard Requirement
          view_print_salesperson: false,
          view_print_industry: false,
          view_print_geo: false,
          view_print_product_group: false,
          view_print_product: false,
          view_print_product_design: false,
          view_print_paper_quality: false,
          view_print_paper_flute: false,
          view_print_paper_size: false,
          view_print_scoring_tool: false,
          view_print_color_group: false,
          view_print_color: false,
          view_print_finishing: false,
          view_print_stitch_wire: false,
          view_print_chemical_coat: false,
          view_print_reinforcement_tape: false,
          view_print_bundling_string: false,
          view_print_wrapping_material: false,
          view_print_glueing_material: false,
          view_print_machine: false,
          define_customer_group: false,
          obsolete_reactive_customer_group: false,
          update_customer_account: false,
          obsolete_reactive_customer_ac: false,
          define_customer_alternate_address: false,
          view_print_customer_group: false,
          view_print_customer_account: false,
          view_print_customer_alternate_address: false,
          update_mc: false,
          approve_mc: false,
          release_approved_mc: false,
          obsolete_reactive_mc: false,
          view_print_mc: false,
          view_print_mc_maintenance_log: false,
          prepare_mc_so: false,
          prepare_sb_so: false,
          prepare_jit_so: false,
          print_so: false,
          cancel_so: false,
          amend_so: false,
          amend_approved_so: false,
          amend_so_price: false,
          amend_approved_so_price: false,
          close_so: false,
          close_so_by_period: false,
          unclose_so: false,
          resubmit_rejected_so: false,
          release_wo_by_so: false,
          print_so_log: false,
          print_so_jit_tracking: false,
          define_so_config: false,
          define_so_period: false,
          define_so_rough_cut: false,
          define_ac_auto_wo: false,
          define_mc_auto_wo: false,
          print_so_period: false,
          print_so_rough_cut: false,
          print_ac_auto_wo: false,
          print_mc_auto_wo: false,
          define_report_format: false,
          print_rough_cut_report: false,
          print_so_report: false,
          print_so_cancel_report: false,
          customer_service_dashboard: false,
          warehouse_management: false,
          define_analysis_code: false,
          define_transport_contractor: false,
          define_vehicle_class: false,
          obsolete_unobsolete_vehicle_class: false,
          define_vehicle: false,
          obsolete_unobsolete_vehicle: false,
          define_dorn_code: false,
          define_greeting_message: false,
          define_alternate_unit: false,
          define_master_card_alternate_unit: false,
          define_dorder_group: false,
          define_users_dorder_group: false,
          view_print_analysis_code: false,
          view_print_vehicle_class: false,
          view_print_vehicle: false,
          prepare_delivery_order_single: false,
          prepare_delivery_order_multiple: false,
          print_delivery_order: false,
          print_do_proforma_invoice: false,
          print_coa_result_by_wo: false,
          print_coa_result_by_so: false,
          amend_delivery_order: false,
          cancel_delivery_order: false,
          reconcile_do_unapplied_fg: false,
          view_print_do_log: false,
          view_print_do_unapplied_fg: false,
          // Invoice Setup
          define_tax_type: false,
          obsolete_unobsolete_tax_type: false,
          define_tax_group: false,
          obsolete_unobsolete_tax_group: false,
          obsolete_unobsolete_customer_sales_tax_index: false,
          define_customer_sales_tax_index: false,
          view_print_tax_type: false,
          view_print_tax_group: false,
          view_print_customer_sales_tax_index: false,
          // Invoice Processing
          prepare_invoice_by_do_current_period: false,
          prepare_invoice_by_do_open_period: false,
          print_invoice: false,
          amend_invoice: false,
          cancel_active_invoice: false,
          view_print_invoice_log: false,
          input_no_faktur: false,
          export_to_coretax: false,
        },
      },
    };
  },
  computed: {
    selectedPermissionsCount() {
      return Object.values(this.form.permissions).filter((permission) => permission)
        .length;
    },
    totalPermissionsCount() {
      return Object.keys(this.form.permissions).length;
    },
  },
  watch: {
    "form.permissions.sales_management"(newValue) {
      if (this.isLoadingPermissions) return;
      // When Sales Management main access is toggled, toggle all related permissions
      const salesPermissions = this.getCategoryPermissions("sales_management");
      salesPermissions.forEach((key) => {
        if (key !== "sales_management") {
          // Don't toggle the main checkbox itself
          this.form.permissions[key] = newValue;
        }
      });
    },
    "form.permissions.system_manager"(newValue) {
      if (this.isLoadingPermissions) return;
      // When System Manager main access is toggled, toggle all related permissions
      const systemManagerPermissions = this.getCategoryPermissions("system_manager");
      systemManagerPermissions.forEach((key) => {
        if (key !== "system_manager") {
          // Don't toggle the main checkbox itself
          this.form.permissions[key] = newValue;
        }
      });
    },
    "form.permissions.warehouse_management"(newValue) {
      if (this.isLoadingPermissions) return;
      // When Warehouse Management main access is toggled, toggle all related permissions
      const warehousePermissions = this.getCategoryPermissions("warehouse_management");
      warehousePermissions.forEach((key) => {
        if (key !== "warehouse_management") {
          // Don't toggle the main checkbox itself
          this.form.permissions[key] = newValue;
        }
      });
    },
    "form.permissions": {
      handler(newPermissions) {
        if (this.isLoadingPermissions) return;
        // Auto-check main access if all submenus are checked
        const salesPermissions = this.getCategoryPermissions("sales_management");
        const subMenuPermissions = salesPermissions.filter(
          (key) => key !== "sales_management"
        );

        // Check if all submenu permissions are checked
        const allSubMenusChecked =
          subMenuPermissions.length > 0 &&
          subMenuPermissions.every((key) => newPermissions[key] === true);

        // Check if at least one submenu is checked
        const someSubMenusChecked = subMenuPermissions.some(
          (key) => newPermissions[key] === true
        );

        // Auto-check main access if all submenus are checked
        if (allSubMenusChecked && !newPermissions.sales_management) {
          this.form.permissions.sales_management = true;
        }

        // Auto-uncheck main access if no submenus are checked
        if (!someSubMenusChecked && newPermissions.sales_management) {
          this.form.permissions.sales_management = false;
        }

        // System Manager main access sync
        const systemManagerPermissions = this.getCategoryPermissions("system_manager");
        const systemManagerSubMenuPermissions = systemManagerPermissions.filter(
          (key) => key !== "system_manager"
        );

        const allSystemManagerSubMenusChecked =
          systemManagerSubMenuPermissions.length > 0 &&
          systemManagerSubMenuPermissions.every((key) => newPermissions[key] === true);

        const someSystemManagerSubMenusChecked = systemManagerSubMenuPermissions.some(
          (key) => newPermissions[key] === true
        );

        if (allSystemManagerSubMenusChecked && !newPermissions.system_manager) {
          this.form.permissions.system_manager = true;
        }

        if (!someSystemManagerSubMenusChecked && newPermissions.system_manager) {
          this.form.permissions.system_manager = false;
        }

        // Warehouse Management main access sync
        const warehousePermissions = this.getCategoryPermissions("warehouse_management");
        const warehouseSubMenuPermissions = warehousePermissions.filter(
          (key) => key !== "warehouse_management"
        );

        const allWarehouseSubMenusChecked =
          warehouseSubMenuPermissions.length > 0 &&
          warehouseSubMenuPermissions.every((key) => newPermissions[key] === true);

        const someWarehouseSubMenusChecked = warehouseSubMenuPermissions.some(
          (key) => newPermissions[key] === true
        );

        if (allWarehouseSubMenusChecked && !newPermissions.warehouse_management) {
          this.form.permissions.warehouse_management = true;
        }

        if (!someWarehouseSubMenusChecked && newPermissions.warehouse_management) {
          this.form.permissions.warehouse_management = false;
        }
      },
      deep: true,
    },
  },
  methods: {
    async openUserModal() {
      this.isLoadingUsers = true;
      try {
        const response = await fetch(`/api/users`, {
          method: "GET",
          headers: {
            "Content-Type": "application/json",
            "X-Requested-With": "XMLHttpRequest",
          },
        });

        const data = await response.json();

        if (response.ok) {
          if (Array.isArray(data)) {
            this.users = data;
          } else if (data && Array.isArray(data.data)) {
            this.users = data.data;
          } else {
            this.users = [];
          }
          this.showUserModal = true;
        } else {
          this.users = [];
        }
      } catch (error) {
        console.error("Error fetching users:", error);
        this.users = [];
      } finally {
        this.isLoadingUsers = false;
      }
    },

    onUserSelected(user) {
      const selectedId = user?.user_id || user?.userID || "";
      if (!selectedId) {
        this.showUserModal = false;
        return;
      }

      this.searchForm.user_id = selectedId;
      this.showUserModal = false;
      this.searchUser();
    },

    async searchUser() {
      this.isSearching = true;
      this.searchMessage = null;

      try {
        const response = await fetch(`/api/users/search/${this.searchForm.user_id}`, {
          method: "GET",
          headers: {
            "Content-Type": "application/json",
            "X-Requested-With": "XMLHttpRequest",
            "X-CSRF-TOKEN":
              this.$page.props.csrf ||
              document.querySelector('meta[name="csrf-token"]')?.getAttribute("content"),
          },
        });

        if (response.status === 403) {
          const errorData = await response.json();
          this.foundUser = null;
          this.searchMessage =
            errorData.message || "Inactive or obsolete users cannot access this menu.";
          this.searchMessageType = "error";
          this.searchMessageIcon = ExclamationCircleIcon;
          return;
        }

        const data = await response.json();

        if (response.ok && data.user) {
          this.foundUser = data.user;
          this.searchMessage = `User ${data.user.official_name} found successfully.`;
          this.searchMessageType = "success";
          this.searchMessageIcon = CheckCircleIcon;

          // Load existing permissions
          if (data.permissions) {
            this.isLoadingPermissions = true;
            try {
              const permissionSet = new Set(data.permissions);

              // Apply all known permissions based on the backend response
              Object.keys(this.form.permissions).forEach((key) => {
                this.form.permissions[key] = permissionSet.has(key);
              });
            } finally {
              // Use nextTick to ensure watchers don't fire on the immediate updates
              this.$nextTick(() => {
                this.isLoadingPermissions = false;
              });
            }
          }
        } else {
          this.foundUser = null;
          this.searchMessage = `User with ID "${this.searchForm.user_id}" not found.`;
          this.searchMessageType = "warning";
          this.searchMessageIcon = ExclamationCircleIcon;
        }
      } catch (error) {
        console.error("Search error:", error);
        this.foundUser = null;
        this.searchMessage = "An error occurred while searching for user.";
        this.searchMessageType = "error";
        this.searchMessageIcon = ExclamationCircleIcon;
      } finally {
        this.isSearching = false;
      }
    },

    async savePermissions() {
      if (!this.foundUser) return;

      this.isSaving = true;

      try {
        const response = await fetch(`/user-permissions/${this.foundUser.userID}`, {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
            "X-Requested-With": "XMLHttpRequest",
            "X-CSRF-TOKEN":
              this.$page.props.csrf ||
              document.querySelector('meta[name="csrf-token"]')?.getAttribute("content"),
          },
          body: JSON.stringify({
            permissions: this.form.permissions,
          }),
        });

        if (response.ok) {
          // Show SweetAlert success notification
          await Swal.fire({
            icon: 'success',
            title: 'Permissions Updated!',
            text: `Permissions for ${this.foundUser.official_name} have been successfully updated.`,
            confirmButtonText: 'OK',
            confirmButtonColor: '#10b981',
            background: '#ffffff',
            color: '#0f172a',
            backdrop: 'rgba(16, 185, 129, 0.2)',
            customClass: {
              popup: 'rounded-2xl shadow-2xl border border-green-100',
              title: 'text-xl font-semibold',
              confirmButton: 'px-6 py-2 rounded-lg'
            },
          });
          
          // Also set the success message for consistency
          this.searchMessage = "Permissions updated successfully!";
          this.searchMessageClass = "bg-green-100 text-green-700 border border-green-200";
          this.searchMessageIcon = CheckCircleIcon;

          // Refresh permissions if this is the current logged-in user
          this.refreshCurrentUserPermissions();
        } else {
          const errorData = await response.json();
          
          // Show SweetAlert error notification
          await Swal.fire({
            icon: 'error',
            title: 'Update Failed!',
            text: errorData.message || "An error occurred while saving permissions.",
            confirmButtonText: 'OK',
            confirmButtonColor: '#ef4444',
            background: '#ffffff',
            color: '#0f172a',
            backdrop: 'rgba(239, 68, 68, 0.2)',
            customClass: {
              popup: 'rounded-2xl shadow-2xl border border-red-100',
              title: 'text-xl font-semibold',
              confirmButton: 'px-6 py-2 rounded-lg'
            },
          });
          
          this.searchMessage =
            errorData.message || "An error occurred while saving permissions.";
          this.searchMessageClass = "bg-red-100 text-red-700 border border-red-200";
          this.searchMessageIcon = ExclamationCircleIcon;
        }
      } catch (error) {
        console.error("Save error:", error);
        
        // Show SweetAlert error notification for unexpected errors
        await Swal.fire({
          icon: 'error',
          title: 'Unexpected Error!',
          text: "An unexpected error occurred while saving permissions. Please try again.",
          confirmButtonText: 'OK',
          confirmButtonColor: '#ef4444',
          background: '#ffffff',
          color: '#0f172a',
          backdrop: 'rgba(239, 68, 68, 0.2)',
          customClass: {
            popup: 'rounded-2xl shadow-2xl border border-red-100',
            title: 'text-xl font-semibold',
            confirmButton: 'px-6 py-2 rounded-lg'
          },
        });
        
        this.searchMessage = "An error occurred while saving permissions.";
        this.searchMessageClass = "bg-red-100 text-red-700 border border-red-200";
        this.searchMessageIcon = ExclamationCircleIcon;
      } finally {
        this.isSaving = false;
      }
    },

    async refreshCurrentUserPermissions() {
      try {
        // Check if the updated user is the current logged-in user
        const currentUser = this.$page.props.auth?.user;
        if (currentUser && currentUser.user_id === this.foundUser.userID) {
          // Refresh permissions by calling the refresh endpoint
          const response = await fetch(`/api/users/${this.foundUser.userID}/permissions/refresh`, {
            method: "POST",
            headers: {
              "Content-Type": "application/json",
              "X-Requested-With": "XMLHttpRequest",
              "X-CSRF-TOKEN":
                this.$page.props.csrf ||
                document.querySelector('meta[name="csrf-token"]')?.getAttribute("content"),
            },
          });

          if (response.ok) {
            const data = await response.json();
            // Update the page props with new permissions
            if (this.$page.props.auth) {
              this.$page.props.auth.permissions = data.permissions;
            }
            
            // Optionally show a notification that permissions have been refreshed
            console.log('Permissions refreshed for current user');
          }
        }
      } catch (error) {
        console.error('Error refreshing permissions:', error);
      }
    },

    toggleAllPermissions() {
      const value = this.selectAllPermissions;
      Object.keys(this.form.permissions).forEach((key) => {
        this.form.permissions[key] = value;
      });
    },

    formatPermissionName(key) {
      // Convert snake_case to Title Case
      return key
        .split("_")
        .map((word) => word.charAt(0).toUpperCase() + word.slice(1))
        .join(" ");
    },

    toggleCategory(category) {
      this.openCategories[category] = !this.openCategories[category];
    },

    getCategoryPermissions(category) {
      const categoryMaps = {
        dashboard: ["dashboard"],
        system_manager: [
          "system_manager",
          "define_user",
          "amend_user_password",
          "define_user_access_permission",
          "copy_paste_user_access_permission",
          "reactive_unobsolete_user",
          "view_print_user",
        ],
        sales_management: Object.keys(this.form.permissions).filter(
          (key) =>
            key.includes("sales") ||
            key.includes("customer") ||
            key.includes("product") ||
            key.includes("mc") ||
            key.includes("so_") ||
            key.includes("_so") ||
            key.includes("industry") ||
            key.includes("geo") ||
            key.includes("color") ||
            key.includes("paper") ||
            key.includes("finishing") ||
            key.includes("scoring") ||
            key.includes("stitch") ||
            key.includes("chemical") ||
            key.includes("reinforcement") ||
            key.includes("bundling") ||
            key.includes("wrapping") ||
            key.includes("glueing") ||
            key.includes("machine")
        ).filter(
          (key) =>
            !key.includes("tax") &&
            !key.includes("faktur") &&
            !key.includes("coretax")
        ),
        warehouse_management: Object.keys(this.form.permissions).filter(
          (key) =>
            key.includes("delivery") ||
            key.includes("warehouse") ||
            key.includes("dorn") ||
            key.includes("invoice") ||
            key.includes("analysis") ||
            key.includes("vehicle") ||
            key.includes("transport") ||
            key.includes("tax") ||
            key.includes("faktur") ||
            key.includes("coretax")
        ),
      };
      return categoryMaps[category] || [];
    },

    getSelectedCountForCategory(category) {
      const permissions = this.getCategoryPermissions(category);
      return permissions.filter((key) => this.form.permissions[key]).length;
    },

    selectAllSalesPermissions() {
      const salesPermissions = this.getCategoryPermissions("sales_management");
      salesPermissions.forEach((key) => {
        this.form.permissions[key] = true;
      });
    },

    clearAllSalesPermissions() {
      const salesPermissions = this.getCategoryPermissions("sales_management");
      salesPermissions.forEach((key) => {
        this.form.permissions[key] = false;
      });
    },

    selectAllWarehousePermissions() {
      const warehousePermissions = this.getCategoryPermissions("warehouse_management");
      warehousePermissions.forEach((key) => {
        this.form.permissions[key] = true;
      });
    },

    clearAllWarehousePermissions() {
      const warehousePermissions = this.getCategoryPermissions("warehouse_management");
      warehousePermissions.forEach((key) => {
        this.form.permissions[key] = false;
      });
    },

    toggleSalesSubcategory(subcategory) {
      if (subcategory === "standard_requirement") {
        this.showAllStandardRequirement = !this.showAllStandardRequirement;
      }
    },

    getSalesStandardRequirementPermissions() {
      return Object.keys(this.form.permissions).filter(
        (key) =>
          key.includes("define_sales") ||
          key.includes("define_product") ||
          key.includes("define_industry") ||
          key.includes("define_geo") ||
          key.includes("define_color") ||
          key.includes("define_paper") ||
          key.includes("define_finishing") ||
          key.includes("define_scoring") ||
          key.includes("view_print_sales") ||
          key.includes("view_print_product") ||
          key.includes("view_print_industry") ||
          key.includes("view_print_geo") ||
          key.includes("view_print_color") ||
          key.includes("view_print_paper") ||
          key.includes("view_print_finishing") ||
          key.includes("view_print_scoring")
      );
    },
  },
};
</script>

<style scoped>
.permission-group:hover {
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

/* Modern Animations */
.animate-fadeIn {
  animation: fadeIn 0.6s cubic-bezier(0.4, 0, 0.2, 1);
}

.animate-slideIn {
  animation: slideIn 0.5s cubic-bezier(0.4, 0, 0.2, 1);
}

@keyframes fadeIn {
  from {
    opacity: 0;
    transform: translateY(-20px) scale(0.95);
  }
  to {
    opacity: 1;
    transform: translateY(0) scale(1);
  }
}

@keyframes slideIn {
  from {
    opacity: 0;
    transform: translateX(-30px);
  }
  to {
    opacity: 1;
    transform: translateX(0);
  }
}

/* Animated Bubbles */
.bubble {
  position: absolute;
  border-radius: 50%;
  background: linear-gradient(135deg, rgba(99, 102, 241, 0.1), rgba(139, 92, 246, 0.1));
  animation: float 15s infinite ease-in-out;
  pointer-events: none;
}

.bubble-1 {
  width: 80px;
  height: 80px;
  left: 10%;
  top: 20%;
  animation-delay: 0s;
  animation-duration: 20s;
}

.bubble-2 {
  width: 120px;
  height: 120px;
  right: 15%;
  top: 10%;
  animation-delay: 2s;
  animation-duration: 25s;
}

.bubble-3 {
  width: 60px;
  height: 60px;
  left: 20%;
  bottom: 30%;
  animation-delay: 4s;
  animation-duration: 18s;
}

.bubble-4 {
  width: 100px;
  height: 100px;
  right: 25%;
  bottom: 20%;
  animation-delay: 6s;
  animation-duration: 22s;
}

.bubble-5 {
  width: 40px;
  height: 40px;
  left: 50%;
  top: 15%;
  animation-delay: 8s;
  animation-duration: 16s;
}

.bubble-6 {
  width: 90px;
  height: 90px;
  left: 5%;
  bottom: 10%;
  animation-delay: 10s;
  animation-duration: 24s;
}

.bubble-7 {
  width: 70px;
  height: 70px;
  right: 5%;
  top: 50%;
  animation-delay: 12s;
  animation-duration: 19s;
}

.bubble-8 {
  width: 50px;
  height: 50px;
  left: 80%;
  bottom: 40%;
  animation-delay: 14s;
  animation-duration: 21s;
}

@keyframes float {
  0%,
  100% {
    transform: translateY(0px) translateX(0px) rotate(0deg);
    opacity: 0.3;
  }
  25% {
    transform: translateY(-20px) translateX(10px) rotate(90deg);
    opacity: 0.6;
  }
  50% {
    transform: translateY(-40px) translateX(-10px) rotate(180deg);
    opacity: 0.4;
  }
  75% {
    transform: translateY(-20px) translateX(15px) rotate(270deg);
    opacity: 0.7;
  }
}

/* Glass morphism effect */
.backdrop-blur-sm {
  backdrop-filter: blur(8px);
  -webkit-backdrop-filter: blur(8px);
}

/* Hover effects */
.hover\:scale-105:hover {
  transform: scale(1.05);
}

/* Focus effects */
.focus\:ring-4:focus {
  box-shadow: 0 0 0 4px rgba(var(--ring-color), 0.3);
}

/* Scoped smooth transitions for interactive elements only */
button,
a,
input,
select,
textarea,
.transition,
.transition-all {
  transition-property: color, background-color, border-color, text-decoration-color, fill,
    stroke, opacity, box-shadow, transform, filter;
  transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
  transition-duration: 200ms;
}

/* Mobile responsiveness */
@media (max-width: 640px) {
  .max-w-6xl {
    max-width: 100%;
    margin: 0;
    padding: 0 1rem;
  }

  .rounded-2xl {
    border-radius: 1rem;
  }

  .p-8 {
    padding: 1.5rem;
  }

  .text-3xl {
    font-size: 1.875rem;
  }
  /* Reduce left indent and inner scroll on mobile */
  .border-l-4 {
    border-left-width: 0 !important;
  }
  .pl-4 {
    padding-left: 0.75rem !important;
  }
  .max-h-96 {
    max-height: none !important;
  }
  .overflow-y-auto {
    overflow-y: visible !important;
  }
}

/* Ensure long permission labels wrap cleanly */
label.flex.items-center span {
  overflow-wrap: anywhere;
  white-space: normal;
}

/* Explicit gradient definitions for category headers */
.dashboard-header {
  background: linear-gradient(to right, #2563eb, #1d4ed8) !important;
  color: white !important;
  border-bottom-color: #1e40af !important;
}

.system-manager-header {
  background: linear-gradient(to right, #4f46e5, #4338ca) !important;
  color: white !important;
  border-bottom-color: #3730a3 !important;
}

.system-manager-header:hover {
  background: linear-gradient(to right, #4338ca, #3730a3) !important;
}

.sales-management-header {
  background: linear-gradient(to right, #16a34a, #15803d) !important;
  color: white !important;
  border-bottom-color: #166534 !important;
}

.sales-management-header:hover {
  background: linear-gradient(to right, #15803d, #166534) !important;
}

.warehouse-management-header {
  background: linear-gradient(to right, #ca8a04, #a16207) !important;
  color: white !important;
  border-bottom-color: #92400e !important;
}

.warehouse-management-header:hover {
  background: linear-gradient(to right, #a16207, #92400e) !important;
}

/* Icon colors for better visibility */
.dashboard-header .fas {
  color: #bfdbfe !important;
}

.system-manager-header .fas {
  color: #c7d2fe !important;
}

.sales-management-header .fas {
  color: #bbf7d0 !important;
}

.warehouse-management-header .fas {
  color: #fef3c7 !important;
}

/* Counter text colors */
.dashboard-header .ml-auto {
  color: #bfdbfe !important;
}

.system-manager-header .ml-auto {
  color: #c7d2fe !important;
}

.sales-management-header .ml-auto {
  color: #bbf7d0 !important;
}

.warehouse-management-header .ml-auto {
  color: #fef3c7 !important;
}

/* Dark mode support */
@media (prefers-color-scheme: dark) {
  .bg-white\/80 {
    background-color: rgba(31, 41, 55, 0.8);
  }

  .text-gray-800 {
    color: #f9fafb;
  }

  .border-gray-200 {
    border-color: #374151;
  }
}
</style>
