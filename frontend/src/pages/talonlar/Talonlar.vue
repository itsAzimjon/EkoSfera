<template>
    <div>
      <q-table
        flat bordered
        ref="tableRef"
        title="Talonlar"
        :rows="talonlar"
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
       
        <template v-slot:body-cell-action="talonlar">
          <q-td :talonlar="talonlar" align="left" width="20px">
            <q-btn
              color="blue-8"
              icon="edit"
              @click="ShowEditModal(talonlar.row)"
              size="sm"
              danse
              class="q-ml-sm"
            />
            <q-btn
              color="red"
              icon="delete"
              @click="ShowDeleteModal(talonlar.row)"
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
            label="Qo'shish"
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
    import AddEdit from "./AddEdit.vue"
  
    export default {
      name:"Talonlar",
      components:{
        AddEdit
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
                    name: 'sana',
                    field: 'sana',
                    label: 'Sana',
                    align: 'left',
                    sortable: true,
                },
                {
                    name: 'buyurtmachi',
                    field: 'buyurtmachi',
                    label: 'Buyurtmachi',
                    align: 'left',
                    sortable: true,
                },
                {
                    name: 'stir',
                    field: 'stir',
                    label: 'Stir',
                    align: 'left',
                    sortable: false,
                },
                {
                    name: 'texnika',
                    field: 'texnika',
                    label: 'Maxsus texnika',
                    align: 'left',
                    sortable: false,
                },
                {
                    name: 'haydovchi',
                    field: 'haydovchi',
                    label: 'Haydovchi',
                    align: 'left',
                    sortable: false,
                },
                {
                    name: 'yuk',
                    field: 'yuk',
                    label: 'Tashilgan yuk(kub)',
                    align: 'left',
                    sortable: false,
                },
                {
                    name: 'chiqindi',
                    field: 'chiqindi',
                    label: 'Chiqindi turi',
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
            visibleColumns:['index','sana','buyurtmachi','stir','texnika','haydovchi','yuk','chiqindi','action'],
            talonlar:[],
            AddData:{
                sana:"",
                buyurtmachi_id:"",
                texnika:"",
                haydovchi:"",
                yuk:"",
                type:"",
            },
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
        async getData(pagination,search){
            var data={
                'page':pagination.page,
                'rowsPerPage':pagination.rowsPerPage,
                'sortBy':pagination.sortBy,
                'filter':search,
                'descending':pagination.descending
            };
            await this.$axios.post('talonlar',data).then(response=>{
                let data=response.data.talonlar.data
                this.talonlar=[]
                let ind = (response.data.talonlar.current_page-1)*response.data.talonlar.per_page+1
                for(var i=0;i<data.length;i++){
                    var json = {
                        "index": ind++,
                        "id": data[i].id,
                        "sana": data[i].sana,
                        "buyurtmachi": data[i].buyurtmachi.name,
                        "stir": data[i].buyurtmachi.stir,
                        "buyurtmachi_id": parseInt(data[i].buyurtmachi_id),
                        "type": parseInt(data[i].type),
                        "yuk": data[i].yuk,
                        "haydovchi": data[i].haydovchi,
                        "texnika": data[i].texnika,
                        "chiqindi": ['','Maishiy','Suyuq'][parseInt(data[i].type)],
                    }
                    this.talonlar.push(json)
                }
                this.pagination.rowsNumber=response.data.talonlar.total

            }).catch(error=>{
                this.$e("Malumot olishda xato")
                this.$checkstatus(error.response.status)
                
            });
        },
        onRequest (props) {
            this.loading=true
            this.pagination=props.pagination
            this.getData(props.pagination,this.search)
            this.loading=false
        
        },
        
        ShowAddModal(){
          
          this.$q.dialog({
            component: AddEdit,
  
            // props forwarded to your custom component
            componentProps: {
                Data:this.AddData,
                text:"Qo'shish"
            }
          }).onOk((from) => {
            this.$axios.post('talonlar/add',from).then(response=>{
                this.$s("Muvaffaqqiyatli qo'shildi")
                this.getData(this.pagination,this.search)
            }).catch(error=>{
                this.$e("Qo'shilmadi")
                this.$checkstatus(error.response.status)
                
            });
            this.AddData={
                id: "",
                sana:"",
                buyurtmachi:"",
                stir:"",
                texnika:"",
                haydovchi:"",
                yuk:"",
                chiqindi:"",
            }

          })
        },
        ShowEditModal(data){
          this.$q.dialog({
            component: AddEdit,
  
            // props forwarded to your custom component
            componentProps: {
              Data:data,
              text:"Foydalanuvchini O'zgartish"
            }
          }).onOk((from) => {
            this.$axios.post('talonlar/edit',from).then(response=>{
                this.$s("Muofaqqiyatli O'zgartirildi")
                this.getData(this.pagination,this.search)
            }).catch(error=>{
                this.$e("Qo'shilmadi")
                this.$checkstatus(error.response.status)
                
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
            this.$axios.post('talonlar/delete',{'id':data.id}).then(response=>{
                this.$s("Muofaqqiyatli O'chirildi")
                this.getData(this.pagination,this.search)
            }).catch(error=>{
                this.$e("O'chira olmadik")
                this.$checkstatus(error.response.status)
                
            })
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