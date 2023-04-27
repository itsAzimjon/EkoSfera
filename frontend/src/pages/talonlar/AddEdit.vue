<template>
     <q-dialog ref="dialog" @hide="onDialogHide">
      <q-card style="min-width: 50%" @keyup.enter="onOKClick" @keyup.esc="hide()">
        <q-card-section class="sticky">
          <div class="text-h6">{{ text }}</div>
        </q-card-section>

        <q-card-section class="q-pt-none margin">
          <q-input dense v-model="form.sana" mask="date" :rules="['date']" label="Sanasi">
            <template v-slot:append>
              <q-icon name="event" class="cursor-pointer">
                <q-popup-proxy cover transition-show="scale" transition-hide="scale">
                  <q-date v-model="form.sana">
                    <div class="row items-center justify-end">
                      <q-btn v-close-popup label="Close" color="primary" flat />
                    </div>
                  </q-date>
                </q-popup-proxy>
              </q-icon>
            </template>
          </q-input>
        </q-card-section>
        
        <q-card-section class="q-pt-none margin">
          <q-select 
            dense
            use-input
            @filter="buyurtmachi_filter"
            v-model="form.buyurtmachi_id" 
            :options="shartnomalar" 
            label='Buyurtmachini tanlang' 
            emit-value
            map-options
          />
        </q-card-section>
        <div class="row">
          <q-card-section class="q-pt-none margin col">
            <q-input 
              dense 
              label="Texnika"
              type="text"
              v-model="form.texnika" 
              ref="texnika"
              :rules="[ val => val.length >1 || 'Texnikani kiriting']"
              autofocus 
            />
          </q-card-section>
          <q-card-section class="q-pt-none margin col">
            <q-input 
              dense 
              label="Haydovchi"
              type="text"
              v-model="form.haydovchi" 
              ref="haydovchi"
              :rules="[ val => val.length >3 || 'Haydovchi kiriting']"
              autofocus 
            />
          </q-card-section>
        </div>
        <div class="row">

          <q-card-section class="q-pt-none margin col">
            <q-input 
              dense 
              label="Yuk"
              type="number"
              v-model="form.yuk" 
              ref="yuk"
              :rules="[ val => val.length >1 || 'Yukni kiriting']"
              autofocus 
            />
          </q-card-section>
          
          
          <q-card-section class="q-pt-none margin col">
            <q-select 
              dense
              use-input
              v-model="form.type" 
              :options="types" 
              label='Chiqindi turini tanlang' 
              emit-value
              map-options
            />
          </q-card-section>
        </div>
        <div class="row">

          <q-card-section class="q-pt-none margin col">
            <q-checkbox v-model="buyurtma" label="Buyurtmaga biriktirish"/>
          </q-card-section>


          <q-card-section class="q-pt-none margin col" v-if="buyurtma">
            <q-select 
              dense
              @filter="buyurtma_filter"
              use-input
              v-model="form.buyurtma_id" 
              :options="buyurtmalar" 
              label='Buyurtmani raqamini kiriting' 
              emit-value
              map-options
            />
          </q-card-section>
          </div>
        <q-card-actions align="right" class="text-blue stickybutton">
          <q-btn color="red"  label="Bekor qilish" @click="hide()"/>
          <q-btn color="blue"  label="Saqlash" @click="onOKClick" :disabled="disabled"/>
        </q-card-actions>
      </q-card>
    </q-dialog>
  </template>
  
  <script>
  export default {
    props: {
        Data:{
            type:Array,
            required:true
        },
        text:{
          type:String,
          required:true
        },
        url:{
          type:String,
          required:true
        }
    },
    data() {
        return {
            buyurtma:false,
            disabled:false,
            form:{
              id: "",
              sana:"",
              buyurtmachi_id:"",
              texnika:"",
              hayadovchi:"",
              yuk:"",
              type:"",
              buyurtma_id:0
            },
            types:[
              {
                value:1,
                label:"Maishiy"
              },
              {
                value:2,
                label:"Suyuq"
              }
            ],
            shartnomalar:[],
            buyurtmalar:[],
            all_buyurtmalar:[],
            all_shartnomalar:[]
        }
    },
    emits: [
      'ok' //, 'hide'
    ],
    mounted() {
        this.form=this.Data
        this.$axios.get('shartnomalar/get').then(response=>{
          let data=response.data.shartnomalar;
          this.shartnomalar=[]
          for(var i=0;i<data.length;i++){
            var json = {
                  value:data[i].id,
                  label:data[i].stir + "  " + data[i].name
              }
              this.shartnomalar.push(json)
          }
          this.all_shartnomalar=this.shartnomalar;
        }).catch(error=>{
          this.$e("Sharnomalar olinmadi")
        });
        this.$axios.get('buyurtma/get').then(response=>{
          let data=response.data.buyurtmalar;
          console.log(data);
          this.buyurtmalar=[]
          for(var i=0;i<data.length;i++){
            if(!data[i].talon)
            {
                var json = {
                    value:data[i].id,
                    label:"#"+data[i].id + "#  " + 
                    data[i].buyurtmachi.name+ "  " + 
                    data[i].sana
                }
                this.buyurtmalar.push(json)
            }
          }
          this.all_buyurtmalar=this.buyurtmalar;
        }).catch(error=>{
          this.$e("Buyurtmalar olinmadi")
        });
    },
    methods: {
      show () {
        this.$refs.dialog.show()
      },
      hide () {
        this.$refs.dialog.hide()
      },
      buyurtmachi_filter (val, update) {
          if (val === '') {
            update(() => {
              this.optionsStore = this.store
            })
            return
          }
          update(() => {
            var needle=val.toLowerCase()
            this.shartnomalar=this.all_shartnomalar.filter((v) => v.label.toLowerCase().indexOf(needle) > -1,)
          })
      },
      buyurtma_filter(val, update) {
          if (val === '') {
            update(() => {
              this.optionsStore = this.store
            })
            return
          }
          update(() => {
            var needle=val.toLowerCase()
            this.buyurtmalar=this.all_buyurtmalar.filter((v) => v.label.toLowerCase().indexOf(needle) > -1,)
          })
      },
      async onOKClick () {
        this.disabled=true
        if(this.form.sana.length<1){
          this.$e("Sanani kiriting")
        }
        else if(this.form.type.length<1){
          this.$e("Turini tanlang")
        }
        else if(this.form.buyurtmachi_id.length<1){
          this.$e("Buyurtmachini tanlang")
        }
        else if(this.form.texnika.length<1){
          this.$e("Texnika tanlang")
        }
        else if(this.form.haydovchi.length<3){
          this.$e("Haydovchi kiriting")
        }
        else if(this.form.yuk.length<1){
          this.$e("Yukni kiriting")
        }
        else{
          await this.$axios.post('talonlar/'+this.url,this.form).then(response=>{
                this.$emit('ok')
                this.$s("Muvaffaqqiyatli qo'shildi")
                this.hide()
            }).catch(error=>{
                this.$e("Qo'shilmadi")
                this.$checkstatus(error.response.status)
                
            });
          
        }
        this.disabled=false
        
      },
  
    //   onDialogHide () {
    //     // required to be emitted
    //     // when QDialog emits "hide" event
    //     this.$emit('hide')
    //   },
  
    //   onCancelClick () {
    //     // we just need to hide the dialog
    //     this.hide()
    //   }
    }
  }
  </script>
  <style scoped>
  .margin{
    /* margin-bottom: 5px; */
  }
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