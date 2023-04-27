<template>
  <div>
    <q-table
      flat
      bordered
      ref="tableRef"
      title="Tashkilotlar"
      :rows="mainData"
      :columns="columns"
      row-key="id"
      v-model:pagination="pagination"
      :loading="loading"
      :filter="search"
      binary-state-sort
      @request="onRequest"
      no-data-label="Ma'lumotlar yo'q"
      no-results-label="Ma'lumotlar topilmadi"
    >
      <template v-slot:body-cell-status="shartnoma">
        <q-td :shartnoma="shartnoma" align="left" width="20px">
          <template v-if="shartnoma.row.status == '1'">
            <q-badge color="green" rounded class="q-mr-sm" />
            Faol
          </template>
          <template v-else>
            <q-badge color="red" rounded class="q-md" />
            Nofaol
          </template>
          <q-popup-edit
            @hide="faol(shartnoma.row.status, shartnoma.row.id)"
            v-model="shartnoma.row.status"
            v-slot="scope"
          >
            <q-select
              filled
              dense
              v-model="scope.value"
              :options="types"
              emit-value
              map-options
              @keyup.enter="scope.set"
            />
          </q-popup-edit>
        </q-td>
      </template>
      <template v-slot:body-cell-action="mainData">
        <q-td :mainData="mainData" align="left" width="20px">
          <q-btn
            color="blue-8"
            icon="edit"
            v-if="ruxsatlar.edit"
            @click="ShowEditModal(mainData.row)"
            size="sm"
            danse
            class="q-ml-sm"
          />
          <q-btn
            color="red"
            icon="delete"
            v-if="ruxsatlar.delete"
            @click="ShowDeleteModal(mainData.row)"
            size="sm"
            danse
            class="q-ml-sm"
          />
        </q-td>
      </template>
      <template v-slot:top>
        <q-btn
          style="font-size: 0.7rem"
          size="sm"
          color="blue"
          v-if="ruxsatlar.add"
          :disable="loading"
          label="Yangi qo'shish"
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
        <q-space />
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
  </div>
</template>
<script>
import addModal from "./addModal.vue";
import { mapState } from "vuex";

export default {
  name: "Tashkilotlar",
  components: {
    addModal,
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
      url: "",
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
          label: "Korxona nomi",
          align: "left",
          sortable: true,
        },
        {
          name: "regions_name",
          field: "regions_name",
          label: "Tuman/Shahar",
          align: "left",
          sortable: false,
        },
        {
          name: "status",
          field: "status",
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
      mainData: [],
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
        descending: pagination.descending,
        holati: pagination.holati,
      };
      await this.$axios
        .post("organization/get", data)
        .then((response) => {
          console.log(response.data.data.data);
          let data = response.data.data.data;
          this.mainData = [];
          let ind =
            (response.data.data.current_page - 1) *
              response.data.data.per_page +
            1;
          for (var i = 0; i < data.length; i++) {
            let regions_name = "";
            let regions_id = [];
            for (var j = 0; j < data[i].regions.length; j++) {
              regions_name += data[i].regions[j].name + ", ";
              regions_id.push(data[i].regions[j].id);
            }
            var json = {
              index: ind++,
              id: data[i].id,
              name: data[i].name,
              regions: data[i].regions,
              status: parseInt(data[i].status),
              regions_name: regions_name,
              regions_id: regions_id,
            };
            this.mainData.push(json);
          }
          this.pagination.rowsNumber = response.data.data.total;
        })
        .catch((error) => {
          this.$e("Ma'lumot olishda xatolik");
          this.$checkstatus(error.response.status);
        });
    },
    async onRequest(props) {
      this.loading = true;
      this.pagination = props.pagination;
      await this.getData(props.pagination, this.search);
      this.loading = false;
    },

    ShowAddModal() {
      this.$q
        .dialog({
          component: addModal,

          // props forwarded to your custom component
          componentProps: {
            Data: [],
            text: "Yangi Tashkilot",
            action: "add",
          },
        })
        .onOk((from) => {
          this.getData(this.pagination, this.search);
        });
    },

    ShowEditModal(data) {
      this.$q
        .dialog({
          component: addModal,

          // props forwarded to your custom component
          componentProps: {
            Data: data,
            text: "Tashkilot Tahrirlash",
            action: "edit",
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
            .post("organization/delete", { id: data.id })
            .then((response) => {
              this.$s("Muofaqqiyatli O'chirildi");
            })
            .catch((error) => {
              this.$e("O'chira olmadik");
              this.$checkstatus(error.response.status);
            });
          this.getData(this.pagination, this.search);
        });
    },
    async faol(val, id) {
      let form = {
        value: val,
        id: id,
      };
      await this.$axios
        .post("organization/holat", form)
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
