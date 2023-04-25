<template>
     <q-dialog ref="dialog" @hide="onDialogHide">
      <q-card style="min-width: 50%" @keyup.enter="onOKClick" @keyup.esc="hide()">
        <q-card-section class="sticky">
          <div class="text-h6">{{ text }}</div>
        </q-card-section>

        <q-card-section class="q-pt-none margin">
          <q-input filled v-model="form.sana" mask="date" :rules="['date']" label="Sanasi">
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
            filled
            use-input
            @filter="buyurtna_filter"
            v-model="form.buyurtmachi_id" 
            :options="shartnomalar" 
            label='Buyurtmachini tanlang' 
            emit-value
            map-options
          />
        </q-card-section>
        <q-card-section class="q-pt-none margin">
          <q-input 
            dense 
            label="Masul"
            type="text"
            v-model="form.masul" 
            ref="masul"
            :rules="[ val => val.length >3 || 'Masul kiriting']"
            autofocus 
          />
        </q-card-section>
        <q-card-section class="q-pt-none margin">
          <q-input 
            dense 
            type="number"
            :rules="[ val => val.length == 9 || 'Telefon raqam kiriting']"
            label="Telefon"
            v-model="form.phone" 
            autofocus 
          >
          <template v-slot:prepend>
            +998
          </template>
          </q-input>
        </q-card-section>
        <q-card-actions align="right" class="text-blue stickybutton">
          <q-btn color="red"  label="Bekor qilish" @click="hide()"/>
          <q-btn color="blue"  label="Saqlash" @click="onOKClick"/>
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
            form:{
              id: "",
              sana:"",
              buyurtmachi_id:"",
              masul:"",
              phone:""
            },
            shartnomalar:[],
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
    },
    methods: {
      show () {
        this.$refs.dialog.show()
      },
      hide () {
        this.$refs.dialog.hide()
      },
      buyurtna_filter (val, update) {
          if (val === '') {
            update(() => {
              this.optionsStore = this.store

              // here you have access to "ref" which
              // is the Vue reference of the QSelect
            })
            return
          }

          update(() => {
            var needle=val.toLowerCase()
            this.shartnomalar=this.all_shartnomalar.filter((v) => v.label.toLowerCase().indexOf(needle) > -1,)
          })
        
       
      },
      
      onOKClick () {
        if(this.form.sana.length<1){
          this.$e("Sanani kiriting")
        }
        else if(this.form.buyurtmachi_id.length<1){
          this.$e("Buyurtmachini tanlang")
        }
        else if(this.form.masul.length<3){
          this.$e("Buyurtmachini tanlang")
        }
        else if(this.form.phone.length!=9){
          this.$e("Telefon raqamni kiriting")
        }
        else{
          this.$axios.post('buyurtma/'+this.url,this.form).then(response=>{
                this.$emit('ok')
                this.$s("Muvaffaqqiyatli qo'shildi")
                this.hide()
            }).catch(error=>{
                this.$e("Qo'shilmadi")
                this.$checkstatus(error.response.status)
                
            });
        }
       
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
    margin-bottom: 5px;
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