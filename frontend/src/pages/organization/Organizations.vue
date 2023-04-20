<template>
    <div>
      <q-table
        flat bordered
        ref="tableRef"
        title="Tashkilotlar"
        :rows="mainData"
        :columns="columns"
        row-key="id"
        v-model:pagination="pagination"
        :loading="loading"
        :filter="search"
        binary-state-sort
        @request="onRequest"
      >
       
        <template v-slot:body-cell-action="mainData">
          <q-td :mainData="mainData" align="left" width="20px">
            <q-btn
              color="blue-8"
              icon="edit"
              @click="ShowEditModal(mainData.row)"
              size="sm"
              danse
              class="q-ml-sm"
            />
            <q-btn
              color="red"
              icon="delete"
              @click="ShowDeleteModal(mainData.row)"
              size="sm"
              danse
              class="q-ml-sm"
            />
          </q-td>
        </template>
        <template v-slot:top>
          <q-btn
            color="blue"
            :disable="loading"
            label="Yangi qo'shish"
            @click="ShowAddModal"
          />
          
          <q-space />
          <!-- <q-select
            v-model="visibleColumns"
            multiple
            outlined
            dense
            options-dense
            display-value="Qatorlar"
            emit-value
            map-options
            :options="columns"
            option-value="name"
            options-cover
            style="min-width: 150px"
          /> -->
          <q-input
            dense
            rounded
            standout="bg-blue-3 text-black"
            v-model="search"
            input-class="text-right"
            class="q-ml-md"
          >
            <template v-slot:append>
              <q-icon v-if="search === ''" name="search" />
              <q-icon
                v-else
                name="clear"
                class="cursor-pointer"
                @click="search = ''"
              />
            </template>
          </q-input>
        </template>
      </q-table>
    </div>
  
  </template>
  <script>
    import addModal from "./addModal.vue"
  
    export default {
      name: "Tashkilotlar",
      components: {
        addModal
      },
      data () {
        return {
            search: "",
            loading: false,
            pagination : {
                sortBy: 'id',
                descending: false,
                page: 1,
                rowsPerPage: 10,
                rowsNumber: 0
            },
            columns: [
              {
                name: 'index',
                label: '#',
                field: 'index',
                align: "left",
                style: 'width: 10px',
                sortable: true
              },
              {
                name: 'name',
                field: 'name',
                label: 'Korxona nomi',
                align: 'left',
                sortable: true,
              },
              {
                name: 'regions_name',
                field: 'regions_name',
                label: 'Tuman/Shahar',
                align: 'left',
                sortable: false,
              },
              {
                name: 'action',
                label: 'Action',
                align: 'left',
                style: 'width: 20px !important',
              },
            ],
            mainData: [],
        }
      },
      async mounted() {
        this.$q.loading.show({
          spinnerColor: 'green',
          spinnerSize: 140,
          backgroundColor: 'blue',
          message: 'Iltimos kuting!!!',
          messageColor: 'black'
        })
        await this.getData(this.pagination,this.search)
        this.url=this.$route.name
        if(!this.$checkRole(this.url,this.permission)){
          this.$router.push({name:"login"})
        }
        let res=this.$getter(this.permission,this.url)
        this.ruxsatlar={
          add:res.add,
          edit:res.edit,
          delete:res.delete,
        }
        this.$q.loading.hide()
      },
      methods: {
        async getData(pagination, search) {
          var data = {
            'page': pagination.page,
            'rowsPerPage': pagination.rowsPerPage,
            'sortBy': pagination.sortBy,
            'filter': search,
            'descending': pagination.descending
          };
          await this.$axios.post('organization/get', data).then(response => {
              console.log(response.data.data.data);
              let data = response.data.data.data
              this.mainData = []
              let ind = (response.data.data.current_page-1) * response.data.data.per_page+1
              for (var i = 0; i < data.length; i++) {
                  let regions_name = "";
                  let regions_id = [];
                  for (var j = 0; j < data[i].regions.length; j++) {
                    regions_name += data[i].regions[j].name + ", ";
                    regions_id.push(data[i].regions[j].id);
                  }
                  var json = {
                      "index": ind++,
                      "id": data[i].id,
                      "name": data[i].name,
                      "regions": data[i].regions,
                      "regions_name": regions_name,
                      "regions_id": regions_id,
                  }
                  this.mainData.push(json)
              }
              this.pagination.rowsNumber = response.data.data.total

          }).catch(error=>{
              this.$e("Ma'lumot olishda xatolik")
              this.$checkstatus(error.response.status)
              
          });
        },
        onRequest (props) {
            console.log(props);
            this.loading=true
            this.pagination=props.pagination
            this.getData(props.pagination,this.search)
            this.loading=false
        
        },
        
        ShowAddModal(){
          this.$q.dialog({
            component: addModal,
  
            // props forwarded to your custom component
            componentProps: {
              Data: [],
              text: "Yangi Tashkilot",
              action: "add"
            }
          }).onOk((from) => {
            this.getData(this.pagination, this.search)
          })
        },

        ShowEditModal(data){
          this.$q.dialog({
            component: addModal,
  
            // props forwarded to your custom component
            componentProps: {
              Data: data,
              text: "Tashkilot Tahrirlash",
              action: "edit"
            }
          }).onOk((from) => {
            this.getData(this.pagination,this.search)
          })
        },
        ShowDeleteModal(data){
          this.$q.dialog({
          title: "O'chirish",
          message: data.name+" ushbu ma'lumotni o'chirmoqchimisiz ",
          ok: {
            label:"Ha",
            push: true
          },
          cancel: {
            push: true,
            label:"Yo'q",
            color: 'negative'
          },
          persistent: true
        }).onOk(() => {
            this.$axios.post('user/delete',{'id':data.id}).then(response=>{
                this.$s("Muofaqqiyatli O'chirildi")
                
            }).catch(error=>{
                this.$e("O'chira olmadik")
                this.$checkstatus(error.response.status)
                
            });
            this.getData(this.pagination,this.search)
        })
        }
        
      },
    }
  </script>
  <style lang="sass">
  .my-sticky-header-column-table
    /* height or max-height is important */
    height: 86vh
  
    /* specifying max-width so the example can
      highlight the sticky column on any browser window */
    max-width: 100%
  
    td:first-child
      /* bg color is important for td; just specify one */
      background-color: #c1f4cd !important
  
    tr th
      position: sticky
      /* higher than z-index for td below */
      z-index: 2
      /* bg color is important; just specify one */
      background: #fff
  
    /* this will be the loading indicator */
    thead tr:last-child th
      /* height of all previous header rows */
      top: 48px
      /* highest z-index */
      z-index: 3
    thead tr:first-child th
      top: 0
      z-index: 1
    tr:first-child th:first-child
      /* highest z-index */
      z-index: 3
  
    td:first-child
      z-index: 1
  
    td:first-child, th:first-child
      position: sticky
      left: 0
  </style>