<template>
  <div>
    <q-table
      flat
      bordered
      ref="tableRef"
      title="Foydalanuvchilar"
      :rows="users"
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
      <template v-slot:body-cell-status="users">
        <q-td :users="users" align="left" width="20px">
          <template v-if="users.row.status == '1'">
            <q-badge color="green" rounded class="q-mr-sm" />
            Faol
          </template>
          <template v-else>
            <q-badge color="red" rounded class="q-md" />
            Nofaol
          </template>
          <q-popup-edit
            @hide="faol(users.row.status, users.row.id)"
            v-model="users.row.status"
            v-slot="scope"
          >
            <q-select
              filled
              v-model="scope.value"
              :options="types"
              emit-value
              dense
              map-options
              @keyup.enter="scope.set"
            />
          </q-popup-edit>
        </q-td>
      </template>
      <template v-slot:body-cell-action="users">
        <q-td :users="users" align="left" width="20px">
          <q-btn
            color="blue-8"
            icon="edit"
            v-if="ruxsatlar.edit"
            @click="ShowEditModal(users.row)"
            size="sm"
            danse
            class="q-ml-sm"
          />
          <q-btn
            color="red"
            icon="delete"
            v-if="ruxsatlar.delete"
            @click="ShowDeleteModal(users.row)"
            size="sm"
            danse
            class="q-ml-sm"
          />
        </q-td>
      </template>
      <template v-slot:top>
        <q-btn
          size="sm"
          v-if="ruxsatlar.add"
          style="font-size: 0.7rem"
          color="blue"
          :disable="loading"
          label="Foydalanuvchi qo'shish"
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
        <q-space />

        <q-input dense v-model="search" input-class="text-right">
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
import userAdd from "./userAdd.vue";
import { mapState } from "vuex";

export default {
  name: "Users",
  components: {
    userAdd,
  },
  computed: {
    ...mapState({
      permission: (state) => state.auth.permission,
    }),
  },
  data() {
    return {
      url: "",
      ruxsatlar: {
        add: false,
        edit: false,
        delete: false,
      },
      search: "",
      loading: false,
      pagination: {
        sortBy: "id",
        descending: false,
        page: 1,
        rowsPerPage: 10,
        rowsNumber: 0,
        label: "asdfk",
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
          label: "FIO",
          align: "left",
          sortable: true,
        },
        {
          name: "email",
          field: "email",
          label: "Email",
          align: "left",
          sortable: true,
        },
        {
          name: "role",
          field: "role",
          label: "Role",
          align: "left",
          sortable: false,
        },
        {
          name: "tuman",
          field: "tuman",
          label: "Biriktirilgan Tuman",
          align: "left",
          sortable: false,
        },
        {
          name: "organizathon",
          field: "organizathon",
          label: "Biriktirilgan tashkilot",
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
      visibleColumns: ["index", "name", "email", "role", "status", "action"],
      users: [],
      AddData: {
        id: "",
        name: "",
        email: "",
        role_id: "",
        tuman_id: "",
        organization_id: "",
        password: "",
        return_password: "",
      },
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
    await this.getData(this.pagination, this.search);
    this.url = this.$route.name;

    let res = this.$getter(this.permission, this.url);
    if (!res.read) {
      this.$router.push({ name: "login" });
    }
    this.ruxsatlar = {
      add: res.add,
      edit: res.edit,
      delete: res.delete,
    };
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
        .post("users", data)
        .then((response) => {
          let data = response.data.users.data;
          this.users = [];
          let ind =
            (response.data.users.current_page - 1) *
              response.data.users.per_page +
            1;
          for (var i = 0; i < data.length; i++) {
            var json = {
              index: ind++,
              id: data[i].id,
              name: data[i].name,
              email: data[i].email,
              status: parseInt(data[i].status),
              role_id: parseInt(data[i].role_id),
              tuman: data[i].tuman ? data[i].tuman.name : "",
              organization: data[i].organization
                ? data[i].organization.name
                : "",
              tuman_id: data[i].tuman_id,
              organization_id: data[i].organization_id,
              role: ["", "Admin", "Korxona admin", "Tuman admin"][
                data[i].role_id
              ],
              password: "",
              return_password: "",
            };
            this.users.push(json);
          }
          this.pagination.rowsNumber = response.data.users.total;
        })
        .catch((error) => {
          this.$e("Mauloat olishda xato");
          this.$checkstatus(error.response.status);
        });
    },
    async onRequest(props) {
      console.log(props);
      this.loading = true;
      this.pagination = props.pagination;
      await this.getData(props.pagination, this.search);
      this.loading = false;
    },
    ShowAddModal() {
      this.$q
        .dialog({
          component: userAdd,
          // props forwarded to your custom component
          componentProps: {
            Data: this.AddData,
            url: "add",
            success: "Muofaqqiyatli qo'shildi",
            error: "Qo'shilmadi",
            text: "Yangi Foydalanuvchi",
          },
        })
        .onOk((from) => {
          this.getData(this.pagination, this.search);
        });
    },
    ShowEditModal(data) {
      this.$q
        .dialog({
          component: userAdd,
          // props forwarded to your custom component
          componentProps: {
            Data: data,
            url: "edit",
            success: "Muofaqqiyatli tahrirlandi",
            error: "Tahrirlanmadi",
            text: "Foydalanuvchini O'zgartish",
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
            .post("user/delete", { id: data.id })
            .then((response) => {
              this.$s("Muofaqqiyatli O'chirildi");
              this.getData(this.pagination, this.search);
            })
            .catch((error) => {
              this.$e("O'chira olmadik");
            });
        });
    },
    async faol(val, id) {
      let form = {
        value: val,
        id: id,
      };
      await this.$axios
        .post("user/holat", form)
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
