<template>
  <div class="body">
    <div class="row">
      <div class="col-6"><h3>Profile:</h3></div>

      <div class="col-6">
        <button
          v-on:click="enabled = true"
          @click="editData"
          id="editBtn"
          type="button"
          class="btn btn-primary"
        >
          Edit
        </button>
      </div>
    </div>

    <div class="row">
      <div class="col-6">
        <img
          :src="this.userData.image"
          alt="Avatar"
          class="BigAvatar align-right"
        />
        <button
          v-bind:class="{ hiden: !enabled }"
          type="button"
          class="btn btn-primary"
          data-bs-toggle="modal"
          data-bs-target="#modal"
        >
          Change profile image
        </button>
      </div>

      <div class="col-6"></div>
    </div>

    <div class="row">
      <div class="col-6">
        <form @submit.prevent="onSubmit">
          <div class="form-group row">
            <label for="firstname" class="col-sm col-form-label"
              >Firstname</label
            >
            <div class="col-sm">
              <input
                :readonly="!enabled"
                type="text"
                v-model="this.userData.firstname"
                v-bind:class="{
                  'form-control-plaintext': !enabled,
                  'form-control': enabled,
                }"
                id="firstname"
              />
            </div>
          </div>
          <div class="form-group row">
            <label for="lastname" class="col-sm col-form-label">Lastname</label>
            <div class="col-sm">
              <input
                :readonly="!enabled"
                v-model="this.userData.lastname"
                type="text"
                v-bind:class="{
                  'form-control-plaintext': !enabled,
                  'form-control': enabled,
                }"
                id="lastname"
              />
            </div>
          </div>
          <div class="form-group row">
            <label for="taxRate" class="col-sm col-form-label">Tax Rate:</label>
            <div class="col-sm">
              <input
                :readonly="!enabled"
                type="number"
                v-bind:class="{
                  'form-control-plaintext': !enabled,
                  'form-control': enabled,
                }"
                id="taxRate"
                v-model="this.userData.taxRate"
              />
            </div>
          </div>

          <div
            v-for="(element, index) in this.userData.currencies"
            :key="index"
          >
            <div class="form-group row">
              <div class="col-sm">Force {{ element.currency }} Exchange</div>
              <div class="col-sm">
                <div class="form-check">
                  <input
                    :disabled="!enabled"
                    class="form-check-input"
                    type="checkbox"
                    id="forceExchange"
                    true-value="1"
                    false-value="0"
                    v-model="this.userData.currencies[index].force_exchange"
                  />
                  <label class="form-check-label" for="gridCheck1">{{
                    this.userData.currencies[index].force_exchange
                  }}</label>
                </div>
              </div>
            </div>

            <div class="form-group row">
              <label for="forceExchange" class="col-sm col-form-label"
                >Force {{ element.currency }} Exchange Amount:</label
              >
              <div class="col-sm">
                <input
                  :readonly="!enabled"
                  type="number"
                  v-bind:class="{
                    'form-control-plaintext': !enabled,
                    'form-control': enabled,
                  }"
                  v-model="element.force_exchange_amount"
                  id="forceExchangeAmount"
                  ref="forceExchangeAmount"
                />
              </div>
            </div>
          </div>

          <div class="form-group row">
            <label for="forceExchange" class="col-sm col-form-label"
              >Notification Period:</label
            >
            <div class="col-sm">
              <select
                :disabled="!enabled"
                class="custom-select my-1 mr-sm-2"
                id="notificationPeriod"
                v-model="this.userData.notificationPeriod"
              >
                <option value="quarter">quarter</option>
                <option value="month">month</option>
                <option value="none">none</option>
              </select>
            </div>
          </div>

          <div class="form-group row">
            <div class="col-sm">
              <button
                v-on:click="enabled = false"
                type="submit"
                class="btn btns btn-primary"
                v-bind:class="{ hiden: !enabled }"
              >
                Save
              </button>

              <button
                v-on:click="enabled = false"
                type="submit"
                class="btn btns btn-primary"
                v-bind:class="{ hiden: !enabled }"
                @click.prevent="onCancel"
              >
                Cancel
              </button>
            </div>
          </div>
        </form>
      </div>
    </div>

    <div class="modal modal-centered" id="modal" tabindex="-1">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Update Avatar</h5>
            <button
              type="button"
              class="btn-close"
              data-bs-dismiss="modal"
              aria-label="Close"
            ></button>
          </div>
          <div class="modal-body">
            <div>
              <input
                ref="input"
                type="file"
                name="image"
                accept="image/*"
                @change="setImage"
              />

              <div class="content">
                <section class="cropper-area">
                  <div class="img-cropper">
                    <vue-cropper
                      ref="cropper"
                      :aspect-ratio="8 / 8"
                      :src="this.userData.image"
                      preview=".preview"
                    />
                  </div>
                  <div class="actions">
                    <a
                      href="#"
                      role="button"
                      data-bs-dismiss="modal"
                      @click.prevent="cropImage"
                    >
                      Crop
                    </a>
                    <a href="#" role="button" @click.prevent="showFileChooser">
                      Upload Image
                    </a>
                  </div>
                </section>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>



<script>
import { mapActions } from "vuex";
import VueCropper from "vue-cropperjs";
import "cropperjs/dist/cropper.css";

export default {
  name: "ProfileBody",
  components: {
    VueCropper,
  },
  computed: {
  },
  async beforeCreate() {},
  async created() {
    await this.getData();
  },
  data() {
    return {
      enabled: false,
      size: "",
      userData: {
        firstname: "",
        image: "",
        lastname: "",
        taxRate: "",
        notificationPeriod: "",
        currencies: "",
      },
      data: null,
    };
  },
  setup() {},
  mounted() {},
  methods: {
    ...mapActions(["updateUserData"]),

    cropImage() {
      this.userData.image = this.$refs.cropper.getCroppedCanvas().toDataURL();
      this.$store.state.uploadImage = this.$refs.cropper.getCroppedCanvas().toDataURL()
    },
    setImage(e) {
      const file = e.target.files[0];
      if (file.size > 20000000) {
        alert("File To Large");
      } else {
        if (file.type.indexOf("image/") === -1) {
          alert("Please select an image file");
          return;
        }
        if (typeof FileReader === "function") {
          const reader = new FileReader();
          reader.onload = (event) => {
            // rebuild cropperjs with the updated source
            this.$refs.cropper.replace(event.target.result);
          };
          reader.readAsDataURL(file);
        } else {
          alert("Sorry, FileReader API not supported");
        }
      }
    },
    showFileChooser() {
      this.$refs.input.click();
    },
    getData() {
      this.$store.dispatch("getUserData").then(() => {
        this.userData = this.$store.state.userData;
      });
    },
    onCancel() {
      this.userData = this.cachedUserData;
    },
    editData() {
      this.cachedUserData = Object.assign({}, this.userData);
    },
    async onSubmit() {
      this.$store.state.updateUserData = this.userData;
      this.$store.dispatch("updateUserData");
    },
    selectImage() {
      this.$refs.fileInput.click();
    },
  },
};
</script>


    <style>
.BigAvatar {
  vertical-align: left;
  align-self: left;
  width: 150px;
  height: 150px;
  border-radius: 50%;
}

#editBtn {
  float: right;
}

.hiden {
  display: none;
}

.btn {
  margin-left: 30px;
}

body {
  font-family: Arial, Helvetica, sans-serif;
  width: 1024px;
  margin: 0 auto;
}
input[type="file"] {
  display: none;
}
.header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 10px 0 5px 0;
}
.header h2 {
  margin: 0;
}
.header a {
  text-decoration: none;
  color: black;
}
.content {
  display: flex;
  justify-content: space-between;
}
.cropper-area {
  width: 614px;
}
.actions {
  margin-top: 1rem;
}
.actions a {
  display: inline-block;
  padding: 5px 15px;
  background: #0062cc;
  color: white;
  text-decoration: none;
  border-radius: 3px;
  margin-right: 1rem;
  margin-bottom: 1rem;
}
textarea {
  width: 100%;
  height: 100px;
}
.preview-area {
  width: 307px;
}
.preview-area p {
  font-size: 1.25rem;
  margin: 0;
  margin-bottom: 1rem;
}
.preview-area p:last-of-type {
  margin-top: 1rem;
}
.preview {
  width: 100%;
  height: calc(372px * (9 / 16));
  overflow: hidden;
}
.crop-placeholder {
  width: 100%;
  height: 200px;
  background: #ccc;
}
.cropped-image img {
  max-width: 100%;
}
</style>