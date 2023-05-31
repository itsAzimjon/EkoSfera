<template>
  <div>
    <q-table
      flat
      bordered
      ref="tableRef"
      title="Haydovchilar ro'yxati"
      :rows="haydovchilar"
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
      <template v-slot:body-cell-action="haydovchilar">
        <q-td :haydovchilar="haydovchilar" align="left" width="20px">
          <q-btn
            color="blue-8"
            icon="edit"
            v-if="ruxsatlar.edit"
            @click="ShowEditModal(haydovchilar.row)"
            size="sm"
            danse
            class="q-ml-sm"
          />
          <q-btn
            color="red"
            icon="delete"
            v-if="ruxsatlar.delete"
            @click="ShowDeleteModal(haydovchilar.row)"
            size="sm"
            danse
            class="q-ml-sm"
          />
        </q-td>
      </template>
      <template v-slot:top>
        <q-btn
          v-if="ruxsatlar.add"
          color="blue"
          size="sm"
          :disable="loading"
          style="font-size: 0.7rem"
          label="Haydovchi qo'shish"
          @click="ShowAddModal"
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
  </div>
</template>
<script>
import EditAdd from "./EditAdd.vue";
import { mapState } from "vuex";

export default {
  name: "haydovchilar",
  components: {
    EditAdd,
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
          name: "guvohnoma",
          field: "guvohnoma",
          label: "Haydovchilik guvohnomasi",
          align: "left",
          sortable: true,
        },
        {
          name: "karxona",
          field: "karxona",
          label: "Karxona ",
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
      haydovchilar: [],
      AddData: {
        name: "",
        organization_id: "",
        guvohnoma: "",
      },
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

    let res = this.$getter(this.permission, this.url);
    if (!res.read) {
      this.$router.push({ name: "login" });
    }
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
      };
      await this.$axios
        .post("haydovchi", data)
        .then((response) => {
          let data = response.data.haydovchilar.data;
          this.haydovchilar = [];
          let ind =
            (response.data.haydovchilar.current_page - 1) *
              response.data.haydovchilar.per_page +
            1;
          for (var i = 0; i < data.length; i++) {
            var json = {
              index: ind++,
              id: data[i].id,
              name: data[i].name,
              guvohnoma: data[i].guvohnoma,
              organization_id: parseInt(data[i].organization_id),
              karxona: data[i].organization.name,
            };
            this.haydovchilar.push(json);
          }
          this.pagination.rowsNumber = response.data.haydovchilar.total;
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
          component: EditAdd,
          // props forwarded to your custom component
          componentProps: {
            Data: this.AddData,
            url: "add",
            success: "Muofaqqiyatli qo'shildi",
            error: "Qo'shilmadi",
            text: "Yangi Haydovchi",
          },
        })
        .onOk((from) => {
          this.getData(this.pagination, this.search);
        });
    },
    ShowEditModal(data) {
      this.$q
        .dialog({
          component: EditAdd,
          // props forwarded to your custom component
          componentProps: {
            Data: data,
            url: "edit",
            success: "Muofaqqiyatli tahrirlandi",
            error: "Tahrirlanmadi",
            text: "Haydovchini O'zgartish",
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
            .post("haydovchi/delete", { id: data.id })
            .then((response) => {
              this.$s("Muofaqqiyatli O'chirildi");
              this.getData(this.pagination, this.search);
            })
            .catch((error) => {
              this.$e("O'chira olmadik");
            });
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
