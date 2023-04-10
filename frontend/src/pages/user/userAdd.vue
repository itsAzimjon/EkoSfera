<template>
     <q-dialog ref="dialog" @hide="onDialogHide">
      <q-card style="min-width: 50%" @keyup.enter="onOKClick" @keyup.esc="hide()">
        <q-card-section class="sticky">
          <div class="text-h6">{{ text }}</div>
        </q-card-section>

        <q-card-section class="q-pt-none">
          <div class="text-h7">FIO</div>
          <q-input 
            dense 
            v-model="form.name" 
            autofocus 
          />
        </q-card-section>

        <q-card-section class="q-pt-none">
          <div class="text-h7">Email</div>
          <q-input
            dense 
            v-model="form.email" 
            autofocus 
          />
        </q-card-section>

        <q-card-section class="q-pt-none">
          <div class="text-h7">Role</div>
          <q-select 
            filled
            v-model="form.role_id" 
            :options="roles" 
            label='Foydalanuvchi vazifasi' 
            emit-value
            map-options
          />
        </q-card-section>

        <q-card-section class="q-pt-none">
          <div class="text-h7">Parol</div>
          <q-input v-model="form.password" filled :type="isPwd ? 'password' : 'text'" hint="Parol 8ta belgidan iborat bo'lsin">
          <template v-slot:append>
            <q-icon
              :name="isPwd ? 'visibility_off' : 'visibility'"
              class="cursor-pointer"
              @click="isPwd = !isPwd"
            />
          </template>
        </q-input>
        </q-card-section>

        <q-card-section class="q-pt-none">
          <div class="text-h7">Qayta parol</div>
          <q-input v-model="form.return_password" filled :type="isPwd ? 'password' : 'text'" hint="Qayta kiriting">
            <template v-slot:append>
              <q-icon
                :name="isPwd ? 'visibility_off' : 'visibility'"
                class="cursor-pointer"
                @click="isPwd = !isPwd"
              />
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
        }
    },
    data() {
        return {
            isPwd:true,
            form:{
                id: "",
                name: "",
                email: "",
                role_id: "",
                password: "",
                return_password: ""
            },
            roles:[
              {
                value:0,
                label:"Admin"
              },
              {
                value:1,
                label:"User"
              }
            ]
        }
    },
    emits: [
      'ok' //, 'hide'
    ],
    mounted() {
        this.form=this.Data
    },
    methods: {
      show () {
        this.$refs.dialog.show()
      },
      hide () {
        this.$refs.dialog.hide()
      },
  
      onOKClick () {
        if (this.form.name.length<3){
          this.$e("FIO to'liq emas")
        }
        else if(!this.form.email.match( /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|.(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/)){
          this.$e("Email kiriting")
        }
        else if(this.form.role_id.length<1){
          this.$e("Rol tanlang")
        }
        else if(this.form.password.length<8){
          this.$e("Parol 8ta belgidan ko'p bo'lishi kerak")
        }
        else if(this.form.password!=this.form.return_password){
          this.$e("Ikki parol bir biriga most emas")
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