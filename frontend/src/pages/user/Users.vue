<template>
    <div>
      <q-table
        flat bordered
        ref="tableRef"
        title="Foydalanuvchilar"
        :rows="users"
        :columns="columns"
        row-key="id"
        v-model:pagination="pagination"
        :loading="loading"
        :filter="search"
        binary-state-sort
        @request="onRequest"
        :visible-columns="visibleColumns"
        no-data-label="Ma'lumotlar yo'q"
        no-results-label="Ma'lumotlar topilmadi"
      >
       
        <template v-slot:body-cell-action="users">
          <q-td :users="users" align="left" width="20px">
            <q-btn
              color="blue-8"
              icon="edit"
              @click="ShowEditModal(users.row)"
              size="sm"
              danse
              class="q-ml-sm"
            />
            <q-btn
              color="red"
              icon="delete"
              @click="ShowDeleteModal(users.row)"
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
            label="Add User"
            @click="ShowAddModal"
          />
          
          <q-space />
          <q-select
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
          />
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
    import userAdd from "./userAdd.vue"
  
    export default {
      name:"Users",
      components:{
        userAdd
      },
      data () {
        return {
            search:"",
            loading:false,
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
                label: 'FIO',
                align: 'left',
                sortable: true,
                },
                {
                name: 'email',
                field: 'email',
                label: 'Email',
                align: 'left',
                sortable: true,
                },
                {
                name: 'tuman',
                field: 'tuman',
                label: 'Tuman',
                align: 'left',
                sortable: false,
                },
                {
                name: 'organizathon',
                field: 'organizathon',
                label: 'Organizathon',
                align: 'left',
                sortable: false,
                },
                {
                name: 'role',
                field: 'role',
                label: 'Role',
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
            visibleColumns:['index','name','email','role','action'],
            users:[],
            AddData:{
                id: "",
                name: "",
                email: "",
                role_id: "",
                tuman_id: "",
                organization_id: "",
                password: "",
                return_password: ""
            },
        }
      },
      mounted() {
        //tokenni tekshiruvdan o'tkazish
        this.getData(this.pagination,this.search)

      },
      methods: {
        getData(pagination,search){
            var data={
                'page':pagination.page,
                'rowsPerPage':pagination.rowsPerPage,
                'sortBy':pagination.sortBy,
                'filter':search,
                'descending':pagination.descending
            };
            this.$axios.post('users',data).then(response=>{
                console.log(response.data);
                let data=response.data.users.data
                this.users=[]
                let ind = (response.data.users.current_page-1)*response.data.users.per_page+1
                for(var i=0;i<data.length;i++){
                    var json = {
                        "index": ind++,
                        "id": data[i].id,
                        "name": data[i].name,
                        "email": data[i].email,
                        "role_id": parseInt(data[i].role_id),
                        "tuman": data[i].tuman?data[i].tuman.name:"",
                        "organization": data[i].organization?data[i].organization.name:"",
                        "tuman_id": data[i].tuman_id,
                        "organization_id": data[i].organization_id,
                        "role": ['','Admin',"Korxona admin","Tuman admin"][data[i].role_id],
                        "password": "",
                        "return_password": ""
                    }
                    this.users.push(json)
                }
                this.pagination.rowsNumber=response.data.users.total

            }).catch(error=>{
                this.$e("Mauloat olishda xato")
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
            component: userAdd,
  
            // props forwarded to your custom component
            componentProps: {
                Data:this.AddData,
                text:"Yangi Foydalanuvchi"
            }
          }).onOk((from) => {
            this.$axios.post('user/add',from).then(response=>{
                this.$s("Muofaqqiyatli qo'shildi")
                this.getData(this.pagination,this.search)
                
            }).catch(error=>{
                this.$e("Qo'shilmadi")
                
            })

          })
        },
        ShowEditModal(data){
          this.$q.dialog({
            component: userAdd,
  
            // props forwarded to your custom component
            componentProps: {
              Data:data,
              text:"Foydalanuvchini O'zgartish"
            }
          }).onOk((from) => {
            this.$axios.post('user/edit',from).then(response=>{
                this.$s("Muofaqqiyatli O'zgartirildi")
                this.getData(this.pagination,this.search)
            }).catch(error=>{
                this.$e("Qo'shilmadi")
                
            })
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
                this.getData(this.pagination,this.search)
            }).catch(error=>{
                this.$e("O'chira olmadik")
            })
        })
        }
      }
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