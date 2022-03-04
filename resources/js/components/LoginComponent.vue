<template>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <div class="card">
          <div class="card-header">Login</div>
          <div class="card-body">
            <form method="POST" action="" @submit.prevent="login($event)">
              <input type="hidden" name="_token" :value="token" />
              <div class="row mb-3">
                <label for="email" class="col-md-4 col-form-label text-md-end"
                  >Email</label
                >

                <div class="col-md-6">
                  <input
                    id="email"
                    type="email"
                    class="form-control"
                    name="email"
                    v-model="email"
                    required
                    autocomplete="email"
                    autofocus
                  />

                  <!-- @error('email')
                  <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                  </span>
                  @enderror -->
                </div>
              </div>

              <div class="row mb-3">
                <label
                  for="password"
                  class="col-md-4 col-form-label text-md-end"
                  >Senha</label
                >

                <div class="col-md-6">
                  <input
                    id="password"
                    type="password"
                    class="form-control"
                    name="password"
                    required
                    autocomplete="current-password"
                    v-model="password"
                  />

                  <!-- @error('password')
                  <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                  </span>
                  @enderror -->
                </div>
              </div>

              <div class="row mb-3">
                <div class="col-md-6 offset-md-4">
                  <div class="form-check">
                    <input
                      class="form-check-input"
                      type="checkbox"
                      name="remember"
                      id="remember"
                    />

                    <label class="form-check-label" for="remember">
                      Lembre-me
                    </label>
                  </div>
                </div>
              </div>

              <div class="row mb-0">
                <div class="col-md-8 offset-md-4">
                  <button type="submit" class="btn btn-primary">Login</button>

                  <a class="btn btn-link" href="#"> Esqueceu sua senha? </a>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: ["token"],
  data() {
    return {
      email: "",
      password: "",
    };
  },
  methods: {
    login(e) {
      let data = new FormData();
      data.append("email", this.email);
      data.append("password", this.password);

      axios
        .post("http://127.0.0.1:8000/api/login", data)
        .then((response) => response.data)
        .then((response) => {
          console.log(response);
          if (response.access_token) {
            document.cookie = "token=" + response.access_token;
            e.target.submit();
          }
        });
    },
  },
};
</script>
