<template>
  <Transition name="slideY">
    <Toast v-if="toast" type="success">
      <template #text>
        {{toastContent}}
      </template>
    </Toast>
  </Transition>
  <div class="px-md-5 pt-md-3 min-vh-100 d-flex flex-column">
    <Navbar />
    <div class="px-4">
      <div class="fs-5 fw-bold mb-3 mt-5 text-darkgray">Manage Contact</div>
      <template v-if="searched">
        <div class="text-sm">Result search <span class="fw-semibold">{{searched}}</span></div>
      </template>
      <div class="d-flex align-items-end justify-content-between">
        <router-link to="/contacts/create" class="text-secodary link"><i class="fa-solid fa-plus me-1"></i> new contact</router-link>
        <div class="d-flex align-items-center">
          <template v-if="search && searched">
            <div @click="refreshContacts" class="cursor-pointer me-3 fs-5 text-primary">
              <i class="fa-solid fa-rotate-right"></i>
            </div>
          </template>
          <div class="position-relative">
            <input v-model="search" type="text" placeholder="Find contacts" class="form-control bg-white pe-5" @keyup.enter="searching">
            <div @click="searching" class="position-absolute end-0 top-0 btn btn-primary shadow text-white"><i class="fa-solid fa-magnifying-glass"></i></div>
          </div>
        </div>
      </div>
      <div class="table-responsive mt-3 mb-5 mx-auto shadow">
          <table class="table mx-auto table-hover overflow-hidden rounded-1">
              <thead>
                  <tr>
                      <th class="px-3 bg-primary text-white">#</th>
                      <th class="px-3 bg-primary text-white">Name</th>
                      <th class="px-3 bg-primary text-white">Email</th>
                      <th class="px-3 bg-primary text-white">Phone</th>
                      <th class="px-3 bg-primary text-white">Date</th>
                      <th class="px-3 bg-primary text-white">Aksi</th>
                  </tr>
              </thead>
              <tbody>
                <template v-if="contacts">
                  <template v-for="(contact, index) in contacts" :key="index">
                      <tr>
                          <td class="px-3 text-primary fw-semibold bg-white">{{ contact.id }}</td>
                          <td class="px-3 fw-semibold text-break bg-white">{{ contact.name }}</td>
                          <td class="px-3 text-break bg-white">{{ contact.email }}</td>
                          <td class="px-3 bg-white">
                            <div class="badge text-bg-primary text-white">{{ '+62 '+contact.phone }}</div>
                          </td>
                          <td class="px-3 fw-semibold text-dark text-break bg-white">{{ contact.created_at }}</td>
                          <td class="px-3 bg-white">
                              <div class="d-flex align-items-center">
                                  <router-link :to="`/contacts/edit/${contact.id}`" class="rounded fw-semibold btn me-2 btn-warning px-2 py-1 text-white">
                                    <i class="fa-solid fa-pencil"></i>
                                  </router-link>
                                  <div type="button" @click="deleteId = contact.id" class="rounded fw-semibold btn btn-danger px-2 py-1 text-white" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                    <i class="fa-solid fa-trash"></i>
                                  </div>
                              </div>
                          </td>
                      </tr>
                  </template>
                  <template v-if="contacts.length == 0">
                    <tr>
                      <td class="py-2 text-dark fw-semibold text-center fs-5" colspan="6">No data</td>
                    </tr>
                  </template>
                </template>
              </tbody>
              <tfoot>
                <tr>
                  <td colspan="6">
                    <div class="d-flex justify-content-end">
                    <template v-if="currentPage > 0">
                      <div class="py-2 d-flex align-items-center justify-content-end">
                        <div class="cursor-pointer pagination text-primary border-0 me-2" v-if="currentPage > 1" @click="getContacts(currentPage - 1)">
                          <i class="fa-solid fa-chevron-left"></i>
                        </div>
                        <template v-if="currentPage > 2">
                          <button :class="`${currentPage == 1 ? 'bg-primary text-white' : ''} pagination me-2 shadow-sm border-0 py-1 px-3 fw-semibold rounded-2`" @click="getContacts(1)">1</button>
                        </template>
                        <template v-if="currentPage > 3">
                          <button class="shadow-sm border-0 py-1 px-3 fw-semibold rounded-2 pagination me-2" disabled>...</button>
                        </template>
                        <template v-for="(page, index) in pagination" :key="index">
                          <button :class="`${page == currentPage ? 'bg-primary text-white' : ''} ${page == lastPage ? '' : 'me-2'} pagination border-0 shadow-sm border-0 py-1 px-3 fw-semibold rounded-2`" @click="getContacts(page)">{{page}}</button>
                        </template>
                        <template v-if="nextLink && currentPage < lastPage-2">
                          <button class="shadow-sm border-0 py-1 px-3 fw-semibold rounded-2 pagination me-2" disabled>...</button>
                        </template>
                        <template v-if="nextLink && currentPage < lastPage-1">
                          <button :class="`${currentPage == lastPage ? 'bg-primary text-white' : ''} shadow-sm border-0 py-1 px-3 fw-semibold rounded-2`" @click="getContacts(lastPage)">{{lastPage}}</button>
                        </template>
                        <div class="cursor-pointer pagination text-primary border-0 mx-2" v-if="nextLink" @click="getContacts(currentPage + 1)">
                          <i class="fa-solid fa-chevron-right"></i>
                        </div>
                      </div>
                    </template>
                  </div>
                  </td>
                </tr>
              </tfoot>
          </table>
      </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Delete Contact - {{ deleteId }}</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure delete this contact?
            </div>
            <div class="modal-footer border-0">
            <form @submit.prevent="deleteContact" method="POST" class="delete-form">
                <button type="button" class="btn fw-semibold btn-outline-dark me-2" data-bs-dismiss="modal">Kembali</button>
                <button type="submit" class="btn fw-semibold btn-danger" data-bs-dismiss="modal">Konfirmasi</button>
            </form>
            </div>
            </div>
        </div>
    </div>
  </div>
</template>

<script>
import { onMounted, ref, computed } from 'vue'
import Navbar from '../../component/navbar.vue'
import Toast from '../../component/toast.vue'
import toastStore from '../../store/toast'
export default {
    components: {
      Navbar,
      Toast
    },
    setup() {
      const toast = ref(false)
      const toastContent = ref('')
      const contacts = ref([])
      const deleteId = ref(null) 
      const currentPage = ref(null)
      const nextLink = ref(null)
      const lastPage = ref(null)
      const search = ref('')
      const searched = ref('')

      const getContacts = (page)=>{
        axios.get(`/api/contacts?page=${page}&search=${search.value}`).then(res => {
          contacts.value = res.data.data
          currentPage.value = res.data.meta.current_page
          lastPage.value = res.data.meta.last_page
          nextLink.value = res.data.links.next
        })
      }

      onMounted(()=>{
        if (toastStore.state.toast) {
          toast.value = true
          toastContent.value = toastStore.state.toast
          setTimeout(removeToast, 2000)
        }
        getContacts('all')
      })

      const searching = () => {
        searched.value = search.value
        getContacts(1)
      }

      const removeToast = () => {
        toastStore.mutations.setToast('')
        toast.value = false
      }

      const deleteContact = () => {
        axios.delete(`/api/contacts/${deleteId.value}`).then(res=>{
          toast.value = true
          toastContent.value = res.data.success
          setTimeout(removeToast, 2000)
          let contactId = contacts.value.findIndex(i => i.id == deleteId.value)
          contacts.value.splice(contactId, 1)
        });
      }

      const pagination = computed(() => {
        let arr = []
        let endLength = currentPage.value+1
        if (!nextLink.value) {
          endLength = currentPage.value
        }
        for(let pageLength = currentPage.value-1; pageLength <= endLength; pageLength++){
          if (pageLength < 1) {
            continue
          }
          arr.push(pageLength)
        }
        return arr
      })

      const refreshContacts = ()=>{
        search.value = ''
        searched.value = ''
        getContacts(1)
      }

      return { toast, toastContent, contacts, deleteId, deleteContact, getContacts, pagination, currentPage, nextLink, lastPage, search, searched, searching, refreshContacts }
    },
}
</script>