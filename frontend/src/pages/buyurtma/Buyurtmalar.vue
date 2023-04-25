<template>
    <div>
      <q-table
        flat bordered
        ref="tableRef"
        title="buyurtma"
        :rows="buyurtma"
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
        <template v-slot:body-cell-holat="buyurtma">
          <q-td :buyurtma="buyurtma" align="left" width="20px">
            <template v-if="buyurtma.row.holati">
              <q-badge  color="green" rounded class="q-mr-sm" />
              Bajarilgan
            </template>
            <template v-else >
              <q-badge color="red" rounded class="q-md" />
              Bajarilmagan
            </template>
          </q-td>
        </template>
        <template v-slot:body-cell-action="buyurtma">
          <q-td :buyurtma="buyurtma" align="left" width="20px">
            <q-btn
              color="green"
              icon="visibility"
              @click="ViewModel(buyurtma.row)"
              size="sm"
              danse
              class="q-ml-sm"
            />
            <q-btn
              color="blue-8"
              icon="edit"
              v-if="ruxsatlar.edit"
              @click="ShowEditModal(buyurtma.row)"
              size="sm"
              danse
              class="q-ml-sm"
            />
            <q-btn
              color="red"
              icon="delete"
              v-if="ruxsatlar.delete"
              @click="ShowDeleteModal(buyurtma.row)"
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
            v-if="ruxsatlar.add"
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
      <q-dialog style="min-width: 50%;" v-model="ViewModelRef" @hide="viewModelClose">
        <q-card class="q-dialog-plugin">
          <q-card-section class="sticky bg-primary text-white">
            <div class="text-h6">To'liq Ma'lumot</div>
          </q-card-section>
          <q-card-section >
            <div>
              <q-list>
                <q-item>
                  Buyurtmachi :  {{ showdata.buyurtmachi }}
                </q-item>
                <q-item>
                  Buyurtmachi STIR :   {{ showdata.stir }}
                </q-item>
                <q-item>
                  Sana :   {{ showdata.sana }}
                </q-item>
                <q-item>
                    Masul :   {{ showdata.masul }}
                </q-item>
                <q-item>
                    Telefon :   {{ showdata.phone }}
                </q-item>
                
              </q-list>
              <template v-if="showdata.talon!=null">
                <div>
                  <div class="text-h6">Biriktirilgan Talon</div>
                  <q-item>
                    Buyurtma bajarilgan sana :   {{ showdata.talon.sana }}
                  </q-item>
                  <q-item>
                    Maxsus texnika :   {{ showdata.talon.texnika }}
                  </q-item>
                  <q-item>
                    Hayadovchi :   {{ showdata.talon.haydovchi }}
                  </q-item>
                  <q-item>
                    Yuk Hajmi :  {{ showdata.talon.yuk }}
                  </q-item>
                  <q-item>
                    Chiqindi turi :  {{ ['','Maishiy','Suyuq'][parseInt(showdata.talon.type)] }}
                  </q-item>
                 
                 
                
                </div>
              </template>
            </div>
          </q-card-section>
          <q-card-actions align="right" class="stickybutton bg-primary">
            <q-btn color="info" label="Yopish" @click="viewModelClose" />
          </q-card-actions>
        </q-card>
      </q-dialog>
    </div>
  
  </template>
  <script>
    import {mapState} from "vuex"
    import AddEdit from "./AddEdit.vue"
  
    export default {
      name:"buyurtma",
      components:{
        AddEdit
      },
      computed: {
        ...mapState({
          permission:state=>state.auth.permission,
        })
      },
      data () {
        return {
            search:"",
            ruxsatlar:{
              add:false,
              edit:false,
              delete:false
            },
            url:"",
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
                    label: 'Buyurtma raqami',
                    field: 'id',
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
                    sortable: false,
                },
                {
                    name: 'stir',
                    field: 'stir',
                    label: 'Stir',
                    align: 'left',
                    sortable: false,
                },
                {
                    name: 'masul',
                    field: 'masul',
                    label: 'Masul shaxs',
                    align: 'left',
                    sortable: true,
                },
                {
                    name: 'phone',
                    field: 'phone',
                    label: 'Telefon',
                    align: 'left',
                    sortable: true,
                },
                {
                    name: 'holat',
                    field: 'holat',
                    label: 'Holati',
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
            visibleColumns:['index','sana','buyurtmachi','stir','masul','phone','holat','action'],
            buyurtma:[],
            AddData:{
                sana:"",
                buyurtmachi_id:"",
                masul:"",
                phone:"",
            },
            ViewModelRef:false,
            showdata:[]
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
            await this.$axios.post('buyurtma',data).then(response=>{
                let data=response.data.buyurtma.data
                console.log(data);
                this.buyurtma=[]
                let ind = (response.data.buyurtma.current_page-1)*response.data.buyurtma.per_page+1
                for(var i=0;i<data.length;i++){
                    var json = {
                        "index": ind++,
                        "id": data[i].id,
                        "sana": data[i].sana,
                        "buyurtmachi": data[i].buyurtmachi.name,
                        "stir": data[i].buyurtmachi.stir,
                        "buyurtmachi_id": parseInt(data[i].buyurtmachi_id),
                        "masul": data[i].masul,
                        "phone": data[i].phone,
                        "holati": data[i].talon!=null,
                        "talon": data[i].talon,
                    }
                    this.buyurtma.push(json)
                }
                this.pagination.rowsNumber=response.data.buyurtma.total

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
            componentProps: {
                Data:this.AddData,
                text:"Qo'shish",
                url:"add"
            }
          }).onOk(() => {
            this.getData(this.pagination,this.search)
            this.AddData={
              sana:"",
                buyurtmachi_id:"",
                masul:"",
                phone:"",
            }
          })
        },
        ShowEditModal(data){
          this.$q.dialog({
            component: AddEdit,
  
            // props forwarded to your custom component
            componentProps: {
              Data:data,
              text:"Foydalanuvchini O'zgartish",
              url:"edit"
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
            this.$axios.post('buyurtma/delete',{'id':data.id}).then(response=>{
                this.$s("Muofaqqiyatli O'chirildi")
                this.getData(this.pagination,this.search)
            }).catch(error=>{
                this.$e("O'chira olmadik")
                this.$checkstatus(error.response.status)
                
            })
        })
        },
        ViewModel(data){
          this.showdata = data
          this.ViewModelRef=true
        },
        viewModelClose(){
          this.ViewModelRef=false
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

  <style scoped>
  .sticky{
    position: sticky; 
    top:0;
    z-index: 100;
    background: inherit;
  }
  .stickybutton{
    position: sticky; 
    bottom: 0;
    z-index: 100;
    background: inherit;
  }
  </style>