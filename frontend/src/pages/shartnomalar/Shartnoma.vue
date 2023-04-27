<template>
  <div>
    <q-table
      flat
      bordered
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
      <template v-slot:body-cell-holat="shartnoma">
        <q-td :shartnoma="shartnoma" align="left" width="20px">
          <template v-if="shartnoma.row.holat == '1'">
            <q-badge color="green" rounded class="q-mr-sm" />
            Faol
          </template>
          <template v-else>
            <q-badge color="red" rounded class="q-md" />
            Nofaol
          </template>
          <q-popup-edit
            @hide="faol(shartnoma.row.holat, shartnoma.row.id)"
            v-model="shartnoma.row.holat"
            v-slot="scope"
          >
            <q-select
              filled
              v-model="scope.value"
              :options="types"
              emit-value
              map-options
              @keyup.enter="scope.set"
            />
          </q-popup-edit>
        </q-td>
      </template>
      <template v-slot:body-cell-action="shartnoma">
        <q-td :shartnoma="shartnoma" align="left" width="20px">
          <q-btn
            color="green"
            icon="visibility"
            @click="ViewModel(shartnoma.row)"
            size="sm"
            danse
            class="q-ml-sm"
          />
          <q-btn
            color="blue-8"
            icon="edit"
            v-if="ruxsatlar.edit"
            @click="ShowEditModal(shartnoma.row)"
            size="sm"
            danse
            class="q-ml-sm"
          />
          <q-btn
            color="red"
            icon="delete"
            v-if="ruxsatlar.delete"
            @click="ShowDeleteModal(shartnoma.row)"
            size="sm"
            danse
            class="q-ml-sm"
          />
        </q-td>
      </template>
      <template v-slot:top>
        <q-btn
          size="sm"
          style="font-size: 0.7rem"
          color="blue"
          :disable="loading"
          v-if="ruxsatlar.add"
          label="Shartnoma qo'shish"
          @click="ShowAddModal"
        />

        <q-space />
        <q-select
          dense
          options-dense
          emit-value
          map-options
          :options="holatlar"
          options-cover
          v-model="pagination.holati"
          @update:model-value="holat_change"
        />
        <q-space />

        <q-select
          v-model="visibleColumns"
          multiple
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
    <q-dialog v-model="ViewModelRef" @hide="viewModelClose">
      <q-card style="min-width: 60%" class="q-dialog-plugin">
        <q-card-section class="sticky bg-primary text-white">
          <div class="text-h6">To'liq Ma'lumot</div>
        </q-card-section>
        <q-card-section>
          <div class="row">
            <div class="col">
              <q-list>
                <q-item class="show_item_shartnoma">
                  <p class="show_p">Tashkilot nomi:</p>
                  <p class="show_data_p">{{ showdata.name }}</p>
                </q-item>
                <q-item class="show_item_shartnoma">
                  <p class="show_p">Shartnoma boshlangan sanasi:</p>
                  <p class="show_data_p">{{ showdata.start }}</p>
                </q-item>
                <q-item class="show_item_shartnoma">
                  <p class="show_p">Shartnoma turi:</p>
                  <p class="show_data_p">{{ showdata.turi }}</p>
                </q-item>
                <q-item class="show_item_shartnoma">
                  <p class="show_p">Manzili:</p>
                  <p class="show_data_p">{{ showdata.manzil }}</p>
                </q-item>
                <q-item class="show_item_shartnoma">
                  <p class="show_p">Rahbari:</p>
                  <p class="show_data_p">{{ showdata.rahbar }}</p>
                </q-item>
                <q-item class="show_item_shartnoma">
                  <p class="show_p">Telefon:</p>
                  <p class="show_data_p">{{ showdata.tel }}</p>
                </q-item>
                <q-item class="show_item_shartnoma">
                  <p class="show_p">Hisob raqami:</p>
                  <p class="show_data_p">{{ showdata.hison_raqam }}</p>
                </q-item>
                <q-item class="show_item_shartnoma">
                  <p class="show_p">G'azna nomi:</p>
                  <p class="show_data_p">{{ showdata.gazna_name }}</p>
                </q-item>
              </q-list>
            </div>
            <div class="col">
              <q-list>
                <q-item class="show_item_shartnoma">
                  <p class="show_p">Tashkilot STIR :</p>
                  <p class="show_data_p">{{ showdata.stir }}</p>
                </q-item>
                <q-item class="show_item_shartnoma">
                  <p class="show_p">Shartnoma tugash sanasi :</p>
                  <p class="show_data_p">{{ showdata.finish }}</p>
                </q-item>
                <q-item class="show_item_shartnoma">
                  <p class="show_p">Shartnoma raqami :</p>
                  <p class="show_data_p">{{ showdata.raqami }}</p>
                </q-item>
                <q-item class="show_item_shartnoma">
                  <p class="show_p">Shartnoma summasi:</p>
                  <p class="show_data_p">{{ showdata.summa }}</p>
                </q-item>
                <q-item class="show_item_shartnoma">
                  <p class="show_p">Bugalter:</p>
                  <p class="show_data_p">{{ showdata.bugalter }}</p>
                </q-item>
                <q-item class="show_item_shartnoma">
                  <p class="show_p">MFO:</p>
                  <p class="show_data_p">{{ showdata.mfo }}</p>
                </q-item>
                <q-item class="show_item_shartnoma">
                  <p class="show_p">
                    QQS to'lovchining ro'yxatdan o'tish sanasi:
                  </p>
                  <p class="show_data_p">{{ showdata.qqs }}</p>
                </q-item>
                <q-item class="show_item_shartnoma">
                  <p class="show_p">G'azna STIR:</p>
                  <p class="show_data_p">{{ showdata.gazna }}</p>
                </q-item>
              </q-list>
            </div>
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
import AddEdit from "./AddEdit.vue";
import { mapState } from "vuex";

export default {
  name: "shartnoma",
  components: {
    AddEdit,
  },
  computed: {
    ...mapState({
      permission: (state) => state.auth.permission,
    }),
  },
  data() {
    return {
      search: "",
      ruxsatlar: {
        add: false,
        edit: false,
        delete: false,
      },
      loading: false,
      pagination: {
        sortBy: "id",
        descending: false,
        page: 1,
        rowsPerPage: 10,
        rowsNumber: 0,
        holati: 0,
      },
      columns: [
        {
          name: "index",
          label: "#",
          field: "index",
          align: "left",
          style: "width: 10px",
          sortable: true,
        },
        {
          name: "name",
          field: "name",
          label: "Tashkilot nomi",
          align: "left",
          sortable: true,
        },
        {
          name: "stir",
          field: "stir",
          label: "Stir",
          align: "left",
          sortable: true,
        },

        {
          name: "turi",
          field: "turi",
          label: "Turi",
          align: "left",
          sortable: true,
        },
        {
          name: "start",
          field: "start",
          label: "Shartnoma sanasi",
          align: "left",
          sortable: true,
        },
        {
          name: "finish",
          field: "finish",
          label: "Shartnoma muddati",
          align: "left",
          sortable: true,
        },
        {
          name: "raqami",
          field: "raqami",
          label: "Shartnoma raqami",
          align: "left",
          sortable: true,
        },
        {
          name: "summa",
          field: "summa",
          label: "Shartnoma summasi",
          align: "left",
          sortable: true,
        },
        {
          name: "manzil_toliq",
          field: "manzil_toliq",
          label: "Manzil",
          align: "left",
          sortable: true,
        },
        {
          name: "rahbar",
          field: "rahbar",
          label: "Rahbar",
          align: "left",
          sortable: true,
        },
        {
          name: "bugalter",
          field: "bugalter",
          label: "Bugalter",
          align: "left",
          sortable: true,
        },
        {
          name: "tel",
          field: "tel",
          label: "Telefon",
          align: "left",
          sortable: true,
        },
        {
          name: "mfo",
          field: "mfo",
          label: "MFO",
          align: "left",
          sortable: true,
        },
        {
          name: "hison_raqam",
          field: "hison_raqam",
          label: "Hisob raqam",
          align: "left",
          sortable: true,
        },
        {
          name: "qqs",
          field: "qqs",
          label: "QQS to'lovchining ro'yxatdan o'tish kodi",
          align: "left",
          sortable: true,
        },
        {
          name: "gazna",
          field: "gazna",
          label: "Gazna CTIR",
          align: "left",
          sortable: true,
        },
        {
          name: "gazna_name",
          field: "gazna_name",
          label: "Gazna Nomi",
          align: "left",
          sortable: true,
        },
        {
          name: "holat",
          field: "holat",
          label: "Holati",
          align: "left",
          sortable: false,
        },
        {
          name: "action",
          label: "Action",
          align: "left",
          style: "width: 20px !important",
        },
      ],
      visibleColumns: [
        "index",
        "name",
        "stir",
        "turi",
        "finish",
        "summa",
        "holat",
        "action",
      ],
      shartnoma: [],
      AddData: {
        id: "",
        index: "",
        name: "",
        stir: "",
        type: "",
        start: "",
        finish: "",
        manzil: "",
        tuman_id: "",
        raqami: "",
        summa: "",
        rahbar: "",
        bugalter: "",
        tel: "",
        mfo: "",
        hison_raqam: "",
        qqs: "",
        varaqa: "",
        photo: "",
        gazna: "",
        gazna_name: "",
      },
      ViewModelRef: false,
      showdata: [],
      types: [
        {
          value: 1,
          label: "Faol",
        },
        {
          value: 0,
          label: "Nofaol",
        },
      ],
      holatlar: [
        {
          value: 2,
          label: "Barchasi",
        },
        {
          value: 0,
          label: "Faol",
        },
        {
          value: 1,
          label: "Nofaol",
        },
      ],
    };
  },
  async mounted() {
    //tokenni tekshiruvdan o'tkazish
    this.$q.loading.show({
      spinnerColor: "green",
      spinnerSize: 140,
      backgroundColor: "blue",
      message: "Iltimos kuting!!!",
      messageColor: "black",
    });
    await this.getData(this.pagination, this.search);
    this.url = this.$route.name;
    if (!this.$checkRole(this.url, this.permission)) {
      this.$router.push({ name: "login" });
    }
    let res = this.$getter(this.permission, this.url);
    this.ruxsatlar = {
      add: res.add,
      edit: res.edit,
      delete: res.delete,
    };
    this.$q.loading.hide();
  },
  methods: {
    async getData(pagination, search) {
      var data = {
        page: pagination.page,
        rowsPerPage: pagination.rowsPerPage,
        sortBy: pagination.sortBy,
        filter: search,
        descending: pagination.descending,
        holati: pagination.holati,
      };
      await this.$axios
        .post("shartnomalar", data)
        .then((response) => {
          let data = response.data.shartnomalar.data;
          this.shartnoma = [];
          let ind =
            (response.data.shartnomalar.current_page - 1) *
              response.data.shartnomalar.per_page +
            1;
          for (var i = 0; i < data.length; i++) {
            var json = {
              index: ind++,
              id: data[i].id,
              name: data[i].name,
              stir: data[i].stir,
              type: parseInt(data[i].type),
              turi: ["", "Yuridik", "Budjet"][parseInt(data[i].type)],
              start: data[i].start,
              finish: data[i].finish,
              raqami: data[i].raqami,
              summa: data[i].summa,
              manzil_toliq: data[i].tuman.name + " " + data[i].manzil,
              manzil: data[i].manzil,
              rahbar: data[i].rahbar,
              bugalter: data[i].bugalter,
              tel: data[i].tel,
              mfo: data[i].mfo,
              hison_raqam: data[i].hison_raqam,
              qqs: data[i].qqs,
              gazna: data[i].gazna,
              tuman_id: parseInt(data[i].tuman_id),
              holat: parseInt(data[i].holati),
              gazna_name: data[i].gazna_name,
            };
            this.shartnoma.push(json);
          }
          this.pagination.rowsNumber = response.data.shartnomalar.total;
        })
        .catch((error) => {
          this.$e("Mauloat olishda xato");
          this.$checkstatus(error.response.status);
        });
    },
    onRequest(props) {
      this.loading = true;
      this.pagination = props.pagination;
      this.getData(props.pagination, this.search);
      this.loading = false;
    },

    ShowAddModal() {
      this.$q
        .dialog({
          component: AddEdit,

          // props forwarded to your custom component
          componentProps: {
            Data: this.AddData,
            text: "Yangi Shartnoma",
            url: "add",
            success: "Muofaqqiyatli qo'shildi",
          },
        })
        .onOk((from) => {
          this.getData(this.pagination, this.search);

          this.AddData = {
            id: "",
            index: "",
            name: "",
            stir: "",
            type: "",
            start: "",
            finish: "",
            manzil: "",
            tuman_id: "",
            raqami: "",
            summa: "",
            rahbar: "",
            bugalter: "",
            tel: "",
            mfo: "",
            hison_raqam: "",
            qqs: "",
            gazna: "",
            gazna_name: "",
          };
        });
    },
    ShowEditModal(data) {
      this.$q
        .dialog({
          component: AddEdit,

          // props forwarded to your custom component
          componentProps: {
            Data: data,
            text: "Shartnoma O'zgartish",
            url: "edit",
            success: "Muofaqqiyatli o'zgartirildi",
          },
        })
        .onOk(() => {
          this.getData(this.pagination, this.search);
        });
    },
    ShowDeleteModal(data) {
      this.$q
        .dialog({
          title: "O'chirish",
          html: true,
          message:
            "<b>" + data.name + "</b> ushbu ma'lumotni o'chirmoqchimisiz ",
          ok: {
            label: "Ha",
            push: true,
          },
          cancel: {
            push: true,
            label: "Yo'q",
            color: "negative",
          },
          persistent: true,
        })
        .onOk(() => {
          this.$axios
            .post("shartnomalar/delete", { id: data.id })
            .then((response) => {
              this.$s("Muofaqqiyatli O'chirildi");
              this.getData(this.pagination, this.search);
            })
            .catch((error) => {
              this.$e("O'chira olmadik");
              this.$checkstatus(error.response.status);
            });
        });
    },
    ViewModel(data) {
      this.showdata = data;
      this.ViewModelRef = true;
    },
    viewModelClose() {
      this.ViewModelRef = false;
    },
    async faol(val, id) {
      let form = {
        value: val,
        id: id,
      };
      await this.$axios
        .post("shartnomalar/holat", form)
        .then((response) => {
          this.getData(this.pagination, this.search);
        })
        .catch((error) => {
          this.$e("Xato");
          this.$checkstatus(error.response.status);
        });
    },
    holat_change() {
      this.getData(this.pagination, this.search);
    },
  },
};
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
.show_item_shartnoma {
  display: flex;
  justify-content: space-between;
}
.show_p {
  font-weight: bold;
  color: blue;
}
.show_data_p {
  font-size: 1rem;
  color: green;
}

.sticky {
  position: sticky;
  top: 0;
  z-index: 100;
  background: inherit;
}
.stickybutton {
  position: sticky;
  bottom: 0;
  z-index: 100;
  background: inherit;
}
</style>
