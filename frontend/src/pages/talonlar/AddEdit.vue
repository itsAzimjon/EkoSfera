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
          <q-input 
            dense 
            v-model="form.buyurtmachi" 
            label="Buyurtmachi"
            autofocus 
            ref="buyurtmachi"
            :rules="[ val => val.length >3 || 'Buyurtmachini kiriting']"
          />
        </q-card-section>
        <q-card-section class="q-pt-none margin">
          <q-input 
            dense 
            label="Stir"
            type="number"
            v-model="form.stir" 
            ref="stir"
            :rules="[ val => val.length >3 || 'Stir kiriting']"
            autofocus 
          />
        </q-card-section>
        <q-card-section class="q-pt-none margin">
          <q-input 
            dense 
            label="Texnika"
            type="text"
            v-model="form.texnika" 
            ref="texnika"
            :rules="[ val => val.length >3 || 'Texnikani kiriting']"
            autofocus 
          />
        </q-card-section>
        <q-card-section class="q-pt-none margin">
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
        <q-card-section class="q-pt-none margin">
          <q-input 
            dense 
            label="Yuk"
            type="text"
            v-model="form.yuk" 
            ref="yuk"
            :rules="[ val => val.length >3 || 'Yukni kiriting']"
            autofocus 
          />
        </q-card-section>
        
        
        <q-card-section class="q-pt-none margin">
          <q-select 
            filled
            v-model="form.chiqindi" 
            :options="types" 
            label='Chiqindi turini tanlang' 
            emit-value
            map-options
          />
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
        }
    },
    data() {
        return {
            form:{
              id: "",
              sana:"",
              buyurtmachi_id:"",
              texnika:"",
              hayadovchi:"",
              yuk:"",
              type:"",
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
            tuman:[]
        }
    },
    emits: [
      'ok' //, 'hide'
    ],
    mounted() {
        this.form=this.Data
        this.$axios.get('tumanlar').then(response=>{
          let data=response.data.tumanlar;
          this.tuman=[]
          for(var i=0;i<data.length;i++){
            var json = {
                  value:data[i].id,
                  label:data[i].name
              }
              this.tuman.push(json)
          }
        }).catch(error=>{
          this.$e("Tuman olishda muammo")
        });
    },
    methods: {
      show () {
        this.$refs.dialog.show()
      },
      hide () {
        this.$refs.dialog.hide()
      },
  
      onOKClick () {
        if(this.form.sana.length<1){
          this.$e("Sharnoma sanasini kiriting")
        }
        else if(this.form.stir.length<3){
          this.$refs.stir.isDirty = false
          this.$e("Stir kiriting")
        }
        else if(this.form.type.length<1){
          this.$refs.type.isDirty = false
          this.$e("Turini tanlang")
        }
        else if(this.form.buyurtmachi.length<1){
          this.$e("Buyurtmachini kiriting")
        }
        else if(this.form.texnika.length<1){
          this.$e("Texnika tanlang")
        }
        else if(this.form.haydovchi.length<3){
          this.$e("Haydovchi kiriting")
        }
        else if(this.form.yuk.length<3){
          this.$e("Yukni kiriting")
        }
        else{
          this.$emit('ok',this.form)
          this.hide()
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