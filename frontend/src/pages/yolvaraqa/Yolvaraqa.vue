<template>
  <div>
    <q-table
      flat
      bordered
      ref="tableRef"
      title="Yo'l Varaqasi"
      :rows="yolvaraqalar"
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
      <template v-slot:body-cell-type="yolvaraqalar">
        <q-td :yolvaraqalar="yolvaraqalar" align="left" width="20px">
          <template v-if="yolvaraqalar.row.type == '1'">
            <q-badge color="green" rounded class="q-mr-sm" />
            Yoqilgan
          </template>
          <template v-else>
            <q-badge color="red" rounded class="q-md" />
            Yopilmagan
          </template>
          <q-popup-edit
            @hide="faol(yolvaraqalar.row.type, yolvaraqalar.row.id)"
            v-model="yolvaraqalar.row.type"
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
      <template v-slot:body-cell-action="yolvaraqalar">
        <q-td :yolvaraqalar="yolvaraqalar" align="left" width="20px">
          <q-btn
            color="green"
            icon="visibility"
            @click="ViewModel(yolvaraqalar.row)"
            size="sm"
            danse
            class="q-ml-sm"
          />
          <q-btn
            color="blue-8"
            icon="edit"
            v-if="ruxsatlar.edit && yolvaraqalar.row.type == 0"
            @click="ShowEditModal(yolvaraqalar.row)"
            size="sm"
            danse
            class="q-ml-sm"
          />
          <q-btn
            color="red"
            icon="delete"
            v-if="ruxsatlar.delete"
            @click="ShowDeleteModal(yolvaraqalar.row)"
            size="sm"
            danse
            class="q-ml-sm"
          />
        </q-td>
      </template>
      <template v-slot:top>
        <q-btn
          color="blue"
          size="sm"
          style="font-size: 0.7rem"
          :disable="loading"
          label="Qo'shish"
          v-if="ruxsatlar.add"
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
          v-model="pagination.type"
          @update:model-value="getData"
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
                  <p class="show_p">Sana:</p>
                  <p class="show_data_p">{{ showdata.sana }}</p>
                </q-item>
                <q-item class="show_item_shartnoma">
                  <p class="show_p">Tuman:</p>
                  <p class="show_data_p">{{ showdata.tuman }}</p>
                </q-item>
                <q-item class="show_item_shartnoma">
                  <p class="show_p">Chiqishda Spidametr ko'rsatkichi:</p>
                  <p class="show_data_p">{{ showdata.start_km }}</p>
                </q-item>
                <q-item class="show_item_shartnoma">
                  <p class="show_p">Kirishdagi Spidametr ko'rsatkichi:</p>
                  <p class="show_data_p">{{ showdata.finish_km }}</p>
                </q-item>
                <q-item class="show_item_shartnoma">
                  <p class="show_p">Chiqish vaqti:</p>
                  <p class="show_data_p">{{ showdata.work_time }}</p>
                </q-item>
                <q-item class="show_item_shartnoma">
                  <p class="show_p">Kirish vaqti:</p>
                  <p class="show_data_p">{{ showdata.free_time }}</p>
                </q-item>
                <q-item class="show_item_shartnoma">
                  <p class="show_p">Texnik ko'rikdan o'tkazgan shaxs:</p>
                  <p class="show_data_p">{{ showdata.texnik }}</p>
                </q-item>
              </q-list>
            </div>
            <div class="col">
              <q-list>
                <q-item class="show_item_shartnoma">
                  <p class="show_p">Korxona :</p>
                  <p class="show_data_p">{{ showdata.korxona }}</p>
                </q-item>
                <q-item class="show_item_shartnoma">
                  <p class="show_p">Haydovchi :</p>
                  <p class="show_data_p">{{ showdata.h_name }}</p>
                </q-item>
                <q-item class="show_item_shartnoma">
                  <p class="show_p">Texnika raqami :</p>
                  <p class="show_data_p">{{ showdata.t_name }}</p>
                </q-item>
                <q-item class="show_item_shartnoma">
                  <p class="show_p">Yoqilg'i turi:</p>
                  <p class="show_data_p">{{ showdata.yoqilgi_turi }}</p>
                </q-item>
                <q-item class="show_item_shartnoma">
                  <p class="show_p">Yoqilg'i miqdori:</p>
                  <p class="show_data_p">{{ showdata.miqdori }}</p>
                </q-item>
                <q-item class="show_item_shartnoma">
                  <p class="show_p">Tibbiy ko'rikdan o'tkazgan shaxs:</p>
                  <p class="show_data_p">{{ showdata.tibbiy }}</p>
                </q-item>
                <q-item class="show_item_shartnoma">
                  <p class="show_p">Holati:</p>
                  <p class="show_data_p">{{ showdata.holat }}</p>
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
import { mapState } from "vuex";
import AddEdit from "./AddEdit.vue";

export default {
  name: "buyurtma",
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
      holatlar: [
        {
          value: 0,
          label: "Yopilmagan",
        },
        {
          value: 1,
          label: "Yopilgan",
        },
      ],
      types: [
        {
          value: 0,
          label: "Yopilmagan",
        },
        {
          value: 1,
          label: "Yopilgan",
        },
      ],
      url: "",
      loading: false,
      pagination: {
        sortBy: "id",
        descending: false,
        page: 1,
        rowsPerPage: 10,
        rowsNumber: 0,
        type: 0,
      },
      columns: [
        {
          name: "index",
          label: "#",
          field: "id",
          align: "left",
          style: "width: 10px",
          sortable: true,
        },
        {
          name: "sana",
          field: "sana",
          label: "Sana",
          align: "left",
          sortable: true,
        },
        {
          name: "korxona",
          field: "korxona",
          label: "Korxona",
          align: "left",
          sortable: false,
        },
        {
          name: "tuman",
          field: "tuman",
          label: "Tuman",
          align: "left",
          sortable: false,
        },
        {
          name: "h_name",
          field: "h_name",
          label: "Haydovchi",
          align: "left",
          sortable: false,
        },
        {
          name: "t_name",
          field: "t_name",
          label: "Texika",
          align: "left",
          sortable: false,
        },
        {
          name: "start_km",
          field: "start_km",
          label: "Garajdan chiqish (km)",
          align: "left",
          sortable: true,
        },
        {
          name: "finish_km",
          field: "finish_km",
          label: "Garajga kirish (km)",
          align: "left",
          sortable: true,
        },
        {
          name: "work_time",
          field: "work_time",
          label: "Ish boshlangan vaqt",
          align: "left",
          sortable: true,
        },
        {
          name: "free_time",
          field: "free_time",
          label: "Ish tugagan vaqt",
          align: "left",
          sortable: true,
        },
        {
          name: "yoqilgi_turi",
          field: "yoqilgi_turi",
          label: "Yoqilg'i turi",
          align: "left",
          sortable: true,
        },
        {
          name: "miqdori",
          field: "miqdori",
          label: "Yoqilg'i miqtori",
          align: "left",
          sortable: true,
        },
        {
          name: "tibbiy",
          field: "tibbiy",
          label: "Tibbiy ko'rikdan o'tkazgan shaxs",
          align: "left",
          sortable: false,
        },
        {
          name: "texnik",
          field: "texnik",
          label: "Texnik ko'rikdan o'tkazgan shaxs",
          align: "left",
          sortable: false,
        },
        {
          name: "type",
          field: "type",
          label: "Holati",
          align: "left",
          sortable: true,
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
        "sana",
        "korxona",
        "tuman",
        "h_name",
        "t_name",
        "type",
        "action",
      ],
      yolvaraqalar: [],
      AddData: {
        id: "",
        sana: "",
        tuman_id: "",
        organization_id: "",
        haydovchi_id: "",
        texnika_id: "",
        start_km: "",
        finish_km: "",
        yoqilgi: "",
        miqdori: "",
        tibbiy: "",
        texnik: "",
        work_time: "",
        free_time: "",
      },
      ViewModelRef: false,
      showdata: [],
    };
  },
  async mounted() {
    this.$q.loading.show({
      spinnerColor: "green",
      spinnerSize: 140,
      backgroundColor: "blue",
      message: "Iltimos kuting!!!",
      messageColor: "black",
    });
    await this.getData();
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
    async getData() {
      var data = {
        page: this.pagination.page,
        rowsPerPage: this.pagination.rowsPerPage,
        sortBy: this.pagination.sortBy,
        filter: this.search,
        descending: this.pagination.descending,
        type: this.pagination.type,
      };
      await this.$axios
        .post("yolvaraqa", data)
        .then((response) => {
          let data = response.data.yolvaraqalar.data;
          console.log(data);
          this.yolvaraqalar = [];
          let ind =
            (response.data.yolvaraqalar.current_page - 1) *
              response.data.yolvaraqalar.per_page +
            1;
          for (var i = 0; i < data.length; i++) {
            var json = {
              index: ind++,
              id: data[i].id,
              sana: data[i].sana,
              korxona: data[i].korxona.name,
              h_name: data[i].haydovchi.name,
              tuman: data[i].tuman.name,
              t_name: data[i].texnika.nomer,
              holati: data[i].type,
              tibbiy: data[i].tibbiy,
              start_km: data[i].start_km,
              finish_km: data[i].finish_km,
              work_time: data[i].work_time,
              free_time: data[i].free_time,
              yoqilgi: parseInt(data[i].yoqilgi),
              yoqilgi_turi: ["Gaz", "Benzil"][parseInt(data[i].yoqilgi)],
              miqdori: data[i].miqdori,
              texnik: data[i].texnik,
              type: data[i].type,
            };
            this.yolvaraqalar.push(json);
          }
          this.pagination.rowsNumber = response.data.yolvaraqalar.total;
        })
        .catch((error) => {
          this.$e("Malumot olishda xato");
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
          componentProps: {
            Data: this.AddData,
            text: "Qo'shish",
            url: "add",
          },
        })
        .onOk(() => {
          this.getData(this.pagination, this.search);
          this.AddData = {
            sana: "",
            buyurtmachi_id: "",
            masul: "",
            phone: "",
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
            text: "Foydalanuvchini O'zgartish",
            url: "edit",
          },
        })
        .onOk((from) => {
          this.getData(this.pagination, this.search);
        });
    },
    ShowDeleteModal(data) {
      this.$q
        .dialog({
          title: "O'chirish",
          message: data.name + " ushbu ma'lumotni o'chirmoqchimisiz ",
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
            .post("yolvaraqa/delete", { id: data.id })
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
        .post("yolvaraqa/holat", form)
        .then((response) => {
          this.getData(this.pagination, this.search);
        })
        .catch((error) => {
          this.$e("Xato");
          this.$checkstatus(error.response.status);
        });
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
