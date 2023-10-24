<template>
  <div class="card">
    <h5 class="card-header">Control de tarimas</h5>
    <div class="table-responsive text-nowrap" v-if="!loading">
      <table class="table">
        <thead>
          <tr>
            <th>Peso Bruto</th>
            <th>No. Cajas</th>
            <th>Peso de caja</th>
            <th>Peso Tarima</th>
            <th>Peso Promedio</th>
            <th>Peso Neto</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody class="table-border-bottom-0">
          <tr v-for="tarima in tarimas" :key="tarima.id">
            <td>
              <div v-if="!tarima.edit">
                {{ tarima.peso_bruto }}
              </div>
              <div v-else>
                <input
                  type="number"
                  v-model="tarima.peso_bruto"
                  class="form-control"
                  @input="debEditAxiosTarimaHandler(tarima.id)"
                />
              </div>
            </td>
            <td>
              <div v-if="!tarima.edit">
                {{ tarima.nocajas }}
              </div>
              <div v-else>
                <input
                  type="number"
                  v-model="tarima.nocajas"
                  class="form-control"
                  @input="debEditAxiosTarimaHandler(tarima.id)"
                />
              </div>
            </td>
            <td>
              <div v-if="!tarima.edit">
                {{ getCajaInfo(tarima.caja_id) }}
              </div>
              <div v-else>
                <select
                  class="form-select"
                  id="estados"
                  v-model="tarima.caja_id"
                  @change="debEditAxiosTarimaHandler(tarima.id)"
                >
                  <option v-for="caja in cajas" :key="caja.id" :value="caja.id">
                    {{ caja.nombre }} ({{ caja.peso }} kg)
                  </option>
                </select>
              </div>
            </td>
            <td>
              <div v-if="!tarima.edit">
                {{ tarima.peso_tarima }}
              </div>
              <div v-else>
                <input
                  type="number"
                  v-model="tarima.peso_tarima"
                  class="form-control"
                  @input="debEditAxiosTarimaHandler(tarima.id)"
                />
              </div>
            </td>

            <td>
              <div v-if="!tarima.edit">
                {{ tarima.peso_prom }}
              </div>

              <div v-else>
                <input
                  type="text"
                  class="form-control"
                  v-model="tarima.peso_prom"
                  disabled
                />
              </div>
            </td>

            <td>
              <div v-if="!tarima.edit">
                {{ tarima.peso_neto }}
              </div>

              <div v-else>
                <input
                  type="text"
                  class="form-control"
                  v-model="tarima.peso_neto"
                  disabled
                />
              </div>
            </td>

            <td>
              <div v-if="!tarima.edit">
                <button
                  type="button"
                  class="btn btn-sm btn-info"
                  @click="tarima.edit = true"
                  :style="{ width: 75 + 'px' }"
                >
                  Editar
                </button>
                <button
                  type="button"
                  class="btn btn-sm btn-danger"
                  @click="destroyTarima(tarima.id)"
                  :style="{ width: 75 + 'px' }"
                >
                  Eliminar
                </button>
              </div>
              <div v-else>
                <button
                  class="btn btn-success btn-sm"
                  :style="{ width: 75 + 'px' }"
                  @click="updateTarima(tarima.id)"
                  :disabled="
                    tarima.peso_bruto == '' ||
                    tarima.nocajas == '' ||
                    tarima.peso_tarima == ''
                  "
                >
                  Guardar
                </button>
                <button
                  type="button"
                  class="btn btn-sm btn-danger"
                  @click="cancelTarima(tarima.id)"
                  :style="{ width: 75 + 'px' }"
                >
                  Cancelar
                </button>
              </div>
            </td>
          </tr>

          <!-- Nueva tarima -->
          <tr>
            <td>
              <input
                type="number"
                class="form-control"
                placeholder="100"
                v-model="newtarima.peso_bruto"
                min="1"
                @input="debAddNewTarimaAxiosHandler"
              />
            </td>
            <td>
              <input
                type="number"
                class="form-control"
                placeholder="10"
                v-model="newtarima.nocajas"
                min="1"
                @input="debAddNewTarimaAxiosHandler"
              />
            </td>
            <td>
              <select
                class="form-select"
                id="estados"
                v-model="newtarima.caja_id"
                @change="debAddNewTarimaAxiosHandler"
              >
                <option value="0">Escoger</option>
                <option v-for="caja in cajas" :key="caja.id" :value="caja.id">
                  {{ caja.nombre }}
                </option>
              </select>
            </td>
            <td>
              <input
                type="number"
                class="form-control"
                placeholder="45"
                v-model="newtarima.peso_tarima"
                min="1"
                @input="debAddNewTarimaAxiosHandler"
              />
            </td>
            <td>
              <input
                type="text"
                class="form-control"
                placeholder=""
                disabled
                v-model="newtarima.peso_prom"
              />
            </td>
            <td>
              <input
                type="text"
                class="form-control"
                placeholder=""
                disabled
                v-model="newtarima.peso_neto"
              />
            </td>
            <td>
              <button
                type="button"
                class="btn btn-sm btn-success"
                @click="storeTarima"
                :disabled="
                  newtarima.peso_bruto == '' ||
                  newtarima.nocajas == '' ||
                  newtarima.peso_tarima == '' ||
                  newtarima.caja_id == 0
                "
              >
                Agregar
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    <div class="d-flex justify-content-center mb-4" v-else>
      <div class="spinner-border spinner-border-lg text-primary" role="status">
        <span class="visually-hidden">Loading...</span>
      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";
import _ from "lodash";
export default {
  name: "Tarimas",
  data() {
    return {
      newtarima: {
        peso_bruto: "",
        nocajas: "",
        peso_cajas: "",
        peso_tarima: "",
        peso_prom: "",
        peso_neto: "",
        caja_id: 0,
        // peso_bruto: 400,
        // nocajas: 10,
        // peso_cajas: 1.50,
        // peso_tarima: "",
        // peso_prom: "",
        // peso_neto: "",
      },
      remision_id: window.remision_id,
      cajas: [],
      tarimas: [],
      tarimasBackup: [],
      loading: true,
    };
  },

  mounted() {
    let cajasPromise = this.getCajas();
    let tarimasPromise = this.getTarimas();

    Promise.all([cajasPromise, tarimasPromise]).then((data) => {
      let cajas = data[0].data;
      let tarimas = data[1].data;
      this.loading = false;

      this.cajas = cajas;
      this.tarimas = this.formatTarimasObj([...tarimas]);
      this.tarimasBackup = this.formatTarimasObj([...tarimas]);

      console.log(cajas);
      console.log(tarimas);
    });
  },

  // created() {
  //   this.getTarimas();
  // },
  methods: {
    formatTarimaObj(tarima) {
      return {
        ...tarima,
        edit: false,
      };
    },
    formatTarimasObj(tarimas) {
      return tarimas.map((tarima) => this.formatTarimaObj(tarima));
    },

    getCajas() {
      let self = this;
      let promesa = axios.get(
        "http://192.168.1.173:8000/api/tarima/getcajas"
      );

      return promesa;
    },

    getTarimas() {
      let remision_id = this.remision_id;
      let self = this;
      let promesa = axios.get(
        "http://192.168.1.173:8000/api/tarima?remisionid=" + remision_id
      );
      // .then((response) => {
      //   this.loading = false;
      //   self.tarimas = this.formatTarimasObj(response.data);
      //   self.tarimasBackup = this.formatTarimasObj(response.data);
      // })
      // .catch((error) => {
      //   console.log(error);
      //   this.loading = false;
      // });

      return promesa;
    },

    update(tarima) {
      return axios.put(
        "http://192.168.1.173:8000/api/tarima/" + tarima.id,
        tarima
      );
    },

    async updateTarima(tarimaid) {
      let tarima = this.tarimas.find((tarima) => tarima.id == tarimaid);

      let response = await this.update(tarima);
      console.log(response);

      let tarimaBackup = this.tarimasBackup.find(
        (tarima) => tarima.id == tarimaid
      );

      tarimaBackup.peso_bruto = response.data.peso_bruto;
      tarimaBackup.nocajas = response.data.nocajas;
      tarimaBackup.caja_id = response.data.caja_id;
      tarimaBackup.peso_tarima = response.data.peso_tarima;
      tarimaBackup.peso_prom = response.data.peso_prom;
      tarimaBackup.peso_neto = response.data.peso_neto;

      tarima.edit = false;
    },

    storeTarima() {
      let remision_id = this.remision_id;
      let newtarima = this.newtarima;
      let self = this;

      const { peso_bruto, nocajas, caja_id, peso_tarima } = newtarima;
      if (!(peso_bruto && nocajas && caja_id !== 0 && peso_tarima))
        return false;
      axios
        .post("http://192.168.1.173:8000/api/tarima", {
          newtarima,
          remision_id,
          tipo_id: 1,
        })
        .then((response) => {
          // let tarima = this.formatTarimaObj(response.data);
          self.tarimas.push(this.formatTarimaObj(response.data));
          self.tarimasBackup.push(this.formatTarimaObj(response.data));

          newtarima.peso_bruto = "";
          newtarima.nocajas = "";
          newtarima.caja_id = 0;
          newtarima.peso_tarima = "";
          newtarima.peso_prom = "";
          newtarima.peso_neto = "";
        })
        .catch((error) => {
          console.log(error);
        });
    },

    destroyTarima(tarimaid) {
      Swal.fire({
        title: "El archivo se eliminara",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Entendido",
      }).then((result) => {
        if (result.isConfirmed) {
          const backupTarimas = this.tarimas;
          const tarima = this.tarimas.find((tarima) => tarima.id === tarimaid);

          this.tarimas = this.tarimas.filter(
            (tarima) => tarima.id !== tarimaid
          );

          axios
            .delete("http://192.168.1.173:8000/api/tarima/" + tarimaid)
            .then((response) => {
              console.log("correcto");
              console.log(response);
              Swal.fire("Eliminado", "", "success");
            })
            .catch((error) => {
              console.log(error);
              this.tarimas.push(tarima);
            });
        }
      });
    },

    cancelTarima(tarimaid) {
      const tarimasBackup = this.tarimasBackup;
      const tarimaBackup = tarimasBackup.find(
        (tarima) => tarima.id === tarimaid
      );

      let tarima = this.tarimas.find((tarima) => tarima.id === tarimaid);

      tarima.peso_bruto = tarimaBackup.peso_bruto;
      tarima.nocajas = tarimaBackup.nocajas;
      tarima.caja_id = tarimaBackup.caja_id;
      tarima.peso_tarima = tarimaBackup.peso_tarima;
      tarima.peso_prom = tarimaBackup.peso_prom;
      tarima.peso_neto = tarimaBackup.peso_neto;
      tarima.edit = false;
    },

    getCalculos({ peso_bruto, nocajas, caja_id, peso_tarima }) {
      console.log(peso_bruto, nocajas, caja_id, peso_tarima);
      return axios.post(
        "http://192.168.1.173:8000/api/tarima/calculos",
        { peso_bruto, nocajas, caja_id, peso_tarima }
      );
    },

    async tarimaEditHandler(tarimaid) {
      // console.log('ejecutado tarimaEditHandler')
      let tarima = this.tarimas.find((tarima) => tarima.id === tarimaid);
      const { peso_bruto, nocajas, caja_id, peso_tarima } = tarima;

      if (peso_bruto && nocajas && caja_id && peso_tarima) {
        console.log(
          "Valdaciones correctas, se procedera ha realizar la llamada axios"
        );
        const { data: pesosResponse } = await this.getCalculos({
          peso_bruto,
          nocajas,
          caja_id,
          peso_tarima,
        });

        const { peso_neto, peso_prom } = pesosResponse;

        tarima.peso_prom = peso_prom;
        tarima.peso_neto = peso_neto;
      }
    },

    async tarimaAddHandler() {
      let self = this;
      const { peso_bruto, nocajas, caja_id, peso_tarima } = self.newtarima;
      
      if (
        peso_bruto &&
        nocajas &&
        (caja_id != 0) &&
        peso_tarima &&
        peso_bruto > 0 &&
        nocajas > 0 &&
        peso_tarima > 0
      ) {
        console.log('Enviar')
        let tarima = await self.getCalculos(self.newtarima);
        const { peso_neto, peso_prom } = tarima.data;
        self.newtarima.peso_prom = peso_prom;
        self.newtarima.peso_neto = peso_neto;
      } else {
        self.newtarima.peso_prom = "";
        self.newtarima.peso_neto = "";
      }
    },

    debEditAxiosTarimaHandler: _.debounce(function (tarimaid) {
      this.tarimaEditHandler(tarimaid);
    }, 350),

    debAddNewTarimaAxiosHandler: _.debounce(function () {
      this.tarimaAddHandler();
    }, 350),

    getCajaInfo(caja_id) {
      let caja = this.cajas.find((caja) => caja.id === caja_id);
      return `${caja.nombre} (${caja.peso} kg)`;
    },
  },
  watch: {
    // newtarima: {
    //   async handler(val) {
    //     let self = this;
    //     const { peso_bruto, nocajas, caja_id, peso_tarima } = val;
    //     if ( (peso_bruto && nocajas && caja_id !== 0 && peso_tarima) && (peso_bruto>0 && nocajas>0 && peso_tarima>0) ) {
    //       let tarima = await self.getCalculos(val);
    //       const { peso_neto, peso_prom } = tarima.data;
    //       self.newtarima.peso_prom = peso_prom;
    //       self.newtarima.peso_neto = peso_neto;
    //     } else {
    //       self.newtarima.peso_prom = "";
    //       self.newtarima.peso_neto = "";
    //     }
    //   },
    //   deep: true,
    // },
  },
};
</script>

<style>
</style>