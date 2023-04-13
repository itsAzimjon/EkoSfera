<template>
    <div>
      <q-table
        flat bordered
        ref="tableRef"
        title="Shartnomalar"
        :rows="shartnoma"
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
       
        <template v-slot:body-cell-type="shartnoma">
            <q-td :shartnoma="shartnoma" align="left" width="20px" >
                <template v-if="shartnoma.row.type==1">
                    Yuridik 
                </template>
                <template v-else>
                    Budjet 
                </template>
            </q-td>
           

        </template>
        <template v-slot:body-cell-action="shartnoma">
          <q-td :shartnoma="shartnoma" align="left" width="20px">
            <q-btn
              color="blue-8"
              icon="edit"
              @click="ShowEditModal(shartnoma.row)"
              size="sm"
              danse
              class="q-ml-sm"
            />
            <q-btn
              color="red"
              icon="delete"
              @click="ShowDeleteModal(shartnoma.row)"
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
            label="Shartnoma qo'shish"
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
      name:"shartnoma",
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
                    name: 'name',
                    field: 'name',
                    label: 'FIO',
                    align: 'left',
                    sortable: true,
                },
                {
                    name: 'stir',
                    field: 'stir',
                    label: 'Stir',
                    align: 'left',
                    sortable: true,
                },
                
                {
                    name: 'type',
                    field: 'type',
                    label: 'Turi',
                    align: 'left',
                    sortable: true,
                },
                {
                    name: 'start',
                    field: 'start',
                    label: 'Shartnoma sanasi',
                    align: 'left',
                    sortable: true,
                },
                {
                    name: 'finish',
                    field: 'finish',
                    label: 'Shartnoma muddati',
                    align: 'left',
                    sortable: true,
                },
                {
                    name: 'raqami',
                    field: 'raqami',
                    label: 'Shartnoma raqami',
                    align: 'left',
                    sortable: true,
                },
                {
                    name: 'summa',
                    field: 'summa',
                    label: 'Shartnoma summasi',
                    align: 'left',
                    sortable: true,
                },
                {
                    name: 'manzil',
                    field: 'manzil',
                    label: 'Manzil',
                    align: 'left',
                    sortable: true,
                },
                {
                    name: 'rahbar',
                    field: 'rahbar',
                    label: 'Rahbar',
                    align: 'left',
                    sortable: true,
                },
                {
                    name: 'bugalter',
                    field: 'bugalter',
                    label: 'Bugalter',
                    align: 'left',
                    sortable: true,
                },
                {
                    name: 'tel',
                    field: 'tel',
                    label: 'Telefon',
                    align: 'left',
                    sortable: true,
                },
                {
                    name: 'mfo',
                    field: 'mfo',
                    label: 'MFO',
                    align: 'left',
                    sortable: true,
                },
                {
                    name: 'hison_raqam',
                    field: 'hison_raqam',
                    label: 'Hison raqam',
                    align: 'left',
                    sortable: true,
                },
                {
                    name: 'qqs',
                    field: 'qqs',
                    label: 'QQS to\'lovchining ro\'yxatdan o\'tish kodi',
                    align: 'left',
                    sortable: true,
                },
                {
                    name: 'varaqa',
                    field: 'varaqa',
                    label: 'Shaxsiy hisob varaqasi',
                    align: 'left',
                    sortable: true,
                },
                {
                    name: 'gazna',
                    field: 'gazna',
                    label: 'Gazna CTIR',
                    align: 'left',
                    sortable: true,
                },
                {
                    name: 'action',
                    label: 'Action',
                    align: 'left',
                    style: 'width: 20px !important',
                },
            ],
            visibleColumns:
            [
                'index',
                'name',
                'stir',
                'type',
                'finish',
                'summa',
                'manzil',
                'action'
            ],
            shartnoma:[],
            AddData:{
                id: "",
                index:'',
                name:'',
                stir:'',
                type:'',
                start:'',
                finish:'',
                manzil:'',
                tuman_id:'',
                raqami:'',
                summa:'',
                rahbar:'',
                bugalter:'',
                tel:'',
                mfo:'',
                hison_raqam:'',
                qqs:'',
                varaqa:'',
                photo:'',
                gazna:'',
            },
        }
      },
      mounted() {
        //tokenni tekshiruvdan o'tkazish
        this.$store.dispatch('check').then(res=>{
          this.getData(this.pagination,this.search)
        }).catch(error=>{
          this.$router.push({name:"login"})
        })

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
            this.$axios.post('shartnomalar',data).then(response=>{
                console.log(response.data);
                let data=response.data.shartnomalar.data
                this.shartnoma=[]
                let ind = (response.data.shartnomalar.current_page-1)*response.data.shartnomalar.per_page+1
                for(var i=0;i<data.length;i++){
                    var json = {
                        index: ind++,
                        id: data[i].id,
                        name:data[i].name,
                        stir:data[i].stir,
                        type:data[i].type,
                        start:data[i].start,
                        finish:data[i].finish,
                        raqami:data[i].raqami,
                        summa:data[i].summa,
                        manzil:data[i].tuman.name+" "+data[i].manzil,
                        rahbar:data[i].rahbar,
                        bugalter:data[i].bugalter,
                        tel:data[i].tel,
                        mfo:data[i].mfo,
                        hison_raqam:data[i].hison_raqam,
                        qqs:data[i].qqs,
                        gazna:data[i].gazna,
                        tuman_id:data[i].tuman_id,
                        holat:data[i].holat,
                    }
                    this.shartnoma.push(json)
                }
                this.pagination.rowsNumber=response.data.shartnomalar.total

            }).catch(error=>{
                this.$e("Mauloat olishda xato")
                
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
                text:"Yangi Shartnoma"
            }
          }).onOk((from) => {
            this.$axios.post('shartnomalar/add',from).then(response=>{
                this.$s("Muofaqqiyatli qo'shildi")
                
            }).catch(error=>{
                this.$e("Qo'shilmadi")
                
            });
            this.AddData={
              id: "",
                index:'',
                name:'',
                stir:'',
                type:'',
                start:'',
                finish:'',
                manzil:'',
                tuman_id:'',
                raqami:'',
                summa:'',
                rahbar:'',
                bugalter:'',
                tel:'',
                mfo:'',
                hison_raqam:'',
                qqs:'',
                gazna:'',
            }
            this.getData(this.pagination,this.search)

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
            this.$axios.post('shartnomalar/edit',from).then(response=>{
                this.$s("Muofaqqiyatli O'zgartirildi")
                
            }).catch(error=>{
                this.$e("Qo'shilmadi")
                
            });
            this.getData(this.pagination,this.search)
          })
        },
        ShowDeleteModal(data){
          this.$q.dialog({
          title: "O'chirish",
          html: true,
          message: "<b>"+data.name+"</b> ushbu ma'lumotni o'chirmoqchimisiz ",
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
            this.$axios.post('shartnomalar/delete',{'id':data.id}).then(response=>{
                this.$s("Muofaqqiyatli O'chirildi")
                
            }).catch(error=>{
                this.$e("O'chira olmadik")
                
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