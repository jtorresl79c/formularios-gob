<template>
  <div class="card">
    <vue-progress-bar></vue-progress-bar>
    <h5 class="card-header">Control de tarimas</h5>

    <div class="text-nowrap machinima" v-if="!loading">
      <table class="table table-hover table-striped table-responsive">
        <thead>
          <tr>
            <th>#</th>
            <th :class="{ 'd-none': tarimatipoid !== 2 }">Remision</th>
            <th>Peso Bruto</th>
            <th>No. Cajas</th>
            <th>Posicion</th>
            <th>Peso de caja</th>
            <th>Peso Tarima</th>
            <th>Peso Promedio</th>
            <th>Peso Neto</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody class="table-border-bottom-0">
          <tr v-for="(tarima, index) in tarimas" :key="tarima.id">
            <td>
              {{ index + 1 }}
             
            </td>
            <td :class="{ 'd-none': tarimatipoid !== 2 }">
              <!-- <a :href="`/remision/${tarima.remision.id}/tarimas`">Ir</a> -->
              <a
                class="btn btn-primary"
                :href="`/remision/${tarima.remision.id}/tarimas`"
                target="_blank"
                role="button"
                >Ir a</a
              >
            </td>
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
            <td width="5%">
            
              <div v-if="!tarima.edit">
                {{ tarima.posicion }}
              </div>
              <div v-else>

                <select
                  class="form-select" id="position"
                  v-model="tarima.posicion"             
                  @change="debEditAxiosTarimaHandler(tarima.id)"
                  >
                  <option disabled value="0">Posicion</option>
                  <option value="1">I</option>
                  <option value="2">M</option>
                  <option value="3">D</option>
                </select>

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
                    {{ caja.nombre }} ({{tarimatipoid == 2 ? Math.round((caja.peso*2.20462)* 100)/100 : caja.peso}} {{tarimatipoid == 2 ? 'Lbs' : 'KG'}})
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
                    tarima.peso_tarima == '' ||
                    tarima.peso_prom == '' ||
                    tarima.peso_neto == '' ||
                    tarima.updating
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
          <!-- <tr :class="{ 'd-none': tarimatipoid == 2 }"> -->
          <tr>
            <td></td>
            <td class="w-25" v-show="tarimatipoid == 2">
              <!-- <select name="" id="">
                <option value="">DASJLDKSAJD</option>
                <option value="">DASJLDKSAJD</option>
                <option value="">DASJLDKSAJD</option>
                <option value="">DASJLDKSAJD</option>
                <option value="">ASDUD897</option>
                <option value="">348FJDLF</option>
              </select> -->
              <v-select
                :options="remisiones"
                :reduce="(remision) => remision.id"
                label="lote"
                v-model="remision_id"
              ></v-select>
            
              <!-- `${lote}-${remisio}` -->
            </td>
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
                class="form-select" id="position"
                v-model="newtarima.position">
                <option disabled selected value="0">Posicion</option>
                <option value="1">I</option>
                <option value="2">M</option>
                <option value="3">D</option>
               
              </select>
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
                  {{ caja.nombre }} ({{tarimatipoid == 2 ? Math.round((caja.peso*2.20462)* 100)/100 : caja.peso}} {{tarimatipoid == 2 ? 'Lbs' : 'Kg'}})
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
                  newtarima.caja_id == 0 ||
                  newtarima.peso_prom == '' ||
                  newtarima.peso_neto == '' ||
                  storingTarimaInProgress ||
                  newtarima.position == 0
                "
              >
                Agregar
              </button>
            </td>
          </tr>
          <tr>
            <td></td>
            <td>Total Bruto: {{this.total_pesobruto }}</td>
            <td>Total Cajas: {{this.total_cajas }}</td>
            <td></td>
            <td>Total Tara: {{this.total_pesotarima}}</td>
            <td></td>
            <td>Total Neto: {{(this.total_pesoneto).toFixed(2)}}</td>
            <td></td>
            <td></td>
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
// import axios from "axios"
import _ from "lodash";
// import httpServices from "./services/httpServices";
import httpServices from "../services/httpServices";

import vSelect from "vue-select";
import "vue-select/dist/vue-select.css";

export default {
  name: "Tarimas",
  props: ["tarimatipoid"],
  components: { vSelect },
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
        position:0,
        // peso_bruto: 400,
        // nocajas: 10,
        // peso_cajas: 1.50,
        // peso_tarima: "",
        // peso_prom: "",
        // peso_neto: "",
      },
      total_pesobruto: 0,
      total_cajas: 0,
      total_pesocaja:0,
      total_pesotarima:0,
      total_pesoprom:0,
      total_pesoneto:0,
      remision_id: null,
      envio_id: window.envio_id,
      cajas: [],
      tarimas: [],
      tarimasBackup: [],
      loading: true,
      storingTarimaInProgress: false,
      remisiones: [],
      selectedRemisionForAdd: null,
    };
  },

  mounted() {
    //remision.foreach(remision=>{
      // let remision =  remision;
    // })

    //nuevaRemision = `${remision.placa} - ${remision.lote}
    // console.log(this.$Progress)
    // console.log("cococ", this.$props.tarimatipoid);

    if(this.$props.tarimatipoid == 1){
      this.remision_id = window.remision_id
    }else{
      this.remision_id = undefined
    }

    this.$Progress.start();

    let cajasPromise = this.getCajas();
    let tarimasPromise = this.getTarimas();
    let remisionesPromise = this.getAllRemisiones();

    Promise.all([cajasPromise, tarimasPromise, remisionesPromise]).then(
      (data) => {
        let cajas = data[0].data;
        let tarimas = data[1].data;
        let remisiones = data[2].data;
        this.loading = false;

        this.cajas = cajas;
        this.tarimas = this.formatTarimasObj([...tarimas]);
        this.tarimasBackup = this.formatTarimasObj([...tarimas]);
        this.remisiones = [...remisiones];
        //console.log(cajas);
        //console.log("antes momo");
        //console.log(tarimas);
        //console.log(tarimas[1].peso_bruto);
        //console.log("momo");
        //console.log(remisiones);
        this.$Progress.finish();

        if (this.tarimatipoid == 2) {
          this.timerID = setInterval(() => this.setTarimasUI(), 2000);
        }
      }
    );
  },

  // created() {
  //   this.getTarimas();
  // },
  methods: {
    getAllRemisiones() {
      return httpServices.get(`/tarima/getAllRemisions`);
    },

    async setTarimasUI() {
      let tarimasPromise = await this.getTarimas();

      let { data } = tarimasPromise;

      // let tarimas

      let allTarimasIdAvailableLocal = this.tarimas.map((t) => t.id);

      data.forEach((element) => {
        if (!allTarimasIdAvailableLocal.includes(element.id)) {
          this.tarimas.push(this.formatTarimaObj(element));
        }
      });

      // console.log(allTarimasIdAvailableLocal)

      // // this.tarimas = this.formatTarimasObj([...tarimas]);
      // // this.tarimasBackup = this.formatTarimasObj([...tarimas]);

      // console.log('fumofu')
      // console.log(data)formatTarimaObj
    },

    formatTarimaObj(tarima) {

        return {
        ...tarima,
        edit: this.tarimatipoid == 2,
        updating: false,
      };
    },
    formatTarimasObj(tarimas) {
    
      this.tarimas.forEach(element => {
        this.total_pesobruto += Math.round((element.peso_bruto)* 100)/100;
        this.total_cajas+= parseInt(element.nocajas);
        this.total_pesotarima+= Math.round((element.peso_tarima)* 100)/100;
        this.total_pesoprom+=  Math.round((element.peso_prom)* 100)/100;
        this. total_pesoneto+= Math.round((element.peso_neto)* 100)/100;
      });


      return tarimas.map((tarima) => this.formatTarimaObj(tarima));
    },

    getCajas() {
      let promesa = httpServices.get("/tarima/getcajas");

      return promesa;
    },

    getTarimas() {
      let remision_id = this.remision_id;
      let envio_id = this.envio_id;

      let id = this.$props.tarimatipoid == 1 ? remision_id : envio_id;
      console.log("dbz", id);

      let promesa = httpServices.get(
        `/tarima?remisionorenvioid=${id}&tarimatipoid=${this.tarimatipoid}`
      );

      return promesa;
    },

    update(tarima) {
      return httpServices.put("/tarima/" + tarima.id, tarima);
    },

    async updateTarima(tarimaid) {
      let tarima = this.tarimas.find((tarima) => tarima.id == tarimaid);
      
      try {
        tarima.updating = true;
        this.$Progress.start();

        console.log('ENVIO ESTOS VALORES');
        console.log(tarima);
        let { data } = await this.update(tarima);
        console.log(data);

        let tarimaBackup = this.tarimasBackup.find(
          (tarima) => tarima.id == tarimaid
        );

        tarimaBackup.peso_bruto = data.peso_bruto;
        tarimaBackup.nocajas = data.nocajas;
        tarimaBackup.caja_id = data.caja_id;
        tarimaBackup.peso_tarima = data.peso_tarima;
        tarimaBackup.peso_prom = data.peso_prom;
        tarimaBackup.peso_neto = data.peso_neto;
        tarimaBackup.position = data.posicion;
        tarima.updating = false;
        this.$Progress.finish();

        tarima.edit = false;
      } catch (error) {
        this.$Progress.fail();
        tarima.updating = false;
        tarima.peso_prom = "";
        tarima.peso_neto = "";
      }
    },

    async storeTarima() {
      // console.log(this.$props.tarimatipoid)
      let remision_id = this.remision_id;
      let newtarima = this.newtarima;

      const { peso_bruto, nocajas, caja_id, peso_tarima, position } = newtarima;
      if (!(peso_bruto && nocajas && caja_id !== 0 && peso_tarima && position !== 0))
        return false;

      this.$Progress.start();

      try {
        // const { data: pesosResponse } = await this.getCalculos({
        this.storingTarimaInProgress = true;
        let { data: tarima } = await httpServices.post("/tarima", {
          newtarima,
          remision_id,
          tarimatipoid: this.$props.tarimatipoid,
          envio_id: this.$props.tarimatipoid == 1 ? 0 : this.envio_id
        });
        

        //console.log("primus");
        //console.log(tarima);

        

        this.storingTarimaInProgress = false;

        this.tarimas.push(this.formatTarimaObj(tarima));
        this.tarimasBackup.push(this.formatTarimaObj(tarima));

        newtarima.peso_bruto = "";
        //newtarima.nocajas = "";
        //newtarima.caja_id = 0;
       // newtarima.peso_tarima = "";
        newtarima.peso_prom = "";
        newtarima.peso_neto = "";

        this.$Progress.finish();

        //console.log(tarima);
      } catch (error) {
        this.$Progress.fail();
        this.storingTarimaInProgress = false;
        newtarima.peso_prom = "";
        newtarima.peso_neto = "";
      }
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

          httpServices
            .delete("/tarima/" + tarimaid)
            .then((response) => {
              Swal.fire({
                position: "top-end",
                icon: "success",
                title: "Eliminado",
                showConfirmButton: false,
                timer: 1500,
              });
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
      console.log(this.tarimatipoid);
      return httpServices.post("/tarima/calculos", {
        peso_bruto,
        nocajas,
        caja_id,
        peso_tarima,
        tarimatipoid: this.tarimatipoid,
      });
    },

    async tarimaEditHandler(tarimaid) {
      // console.log('ejecutado tarimaEditHandler')
      let tarima = this.tarimas.find((tarima) => tarima.id === tarimaid);
      const { peso_bruto, nocajas, caja_id, peso_tarima } = tarima;

      if (peso_bruto && nocajas && caja_id && peso_tarima) {
        // console.log(
        //   "Valdaciones correctas, se procedera ha realizar la llamada axios"
        // );

        try {
          this.$Progress.start();

          const { data: pesosResponse } = await this.getCalculos({
            peso_bruto,
            nocajas,
            caja_id,
            peso_tarima,
          });
          this.$Progress.finish();

          const { peso_neto, peso_prom } = pesosResponse;

          tarima.peso_prom = peso_prom;
          tarima.peso_neto = peso_neto;
        } catch (error) {
          this.$Progress.fail();
          tarima.peso_prom = "";
          tarima.peso_neto = "";
        }
      }
    },

    async tarimaNewTarimaListenerHandler() {
      let self = this;
      const { peso_bruto, nocajas, caja_id, peso_tarima } = self.newtarima;

      if (
        peso_bruto &&
        nocajas &&
        caja_id != 0 &&
        peso_tarima &&
        peso_bruto > 0 &&
        nocajas > 0 &&
        peso_tarima > 0
      ) {
        console.log("Enviar");

        try {
          self.$Progress.start();
          let tarima = await self.getCalculos(self.newtarima);

          const { peso_neto, peso_prom } = tarima.data;

          self.newtarima.peso_prom = peso_prom;
          self.newtarima.peso_neto = peso_neto;

          self.$Progress.finish();
        } catch (error) {
          self.$Progress.fail();

          self.newtarima.peso_prom = "";
          self.newtarima.peso_neto = "";
        }
      } else {
        self.newtarima.peso_prom = "";
        self.newtarima.peso_neto = "";
      }
    },

    debEditAxiosTarimaHandler: _.debounce(function (tarimaid) {
      this.tarimaEditHandler(tarimaid);
    }, 350),

    debAddNewTarimaAxiosHandler: _.debounce(function () {
      this.tarimaNewTarimaListenerHandler();
    }, 350),

    getCajaInfo(caja_id) {
      let caja = this.cajas.find((caja) => caja.id === caja_id);
      let tipo_peso='';
      console.log('cancela');
      console.log('el valor:');
      console.log(caja.tipo_id);
     /*  if(caja.area_id == 2){
        tipo_peso='Lbs';
      }else{ */
        tipo_peso='Kg';
      /* } */

      return `${caja.nombre} (${caja.peso} ${tipo_peso})`;
    },
  },
};
</script>

<style>
/* .machinima{
  overflow: hidden !important;
  position: relative;
  z-index: 1;
} */

/* #vs1__listbox{
  position: absolute;
  z-index:1000000000000000000000000 !important;
  --vs-dropdown-z-index: 100000000000000000000000000000000000;
  --vs-dropdown-bg: #000;
} */
.container-xxl, .container-xl, .container-lg, .container-md, .container-sm, .container{
  max-width: 1500px!important;
}
</style>