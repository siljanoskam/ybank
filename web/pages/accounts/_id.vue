<template>
  <div>
    <div class="container" v-if="loading">loading...</div>

    <div class="container" v-if="!loading">
      <b-card :header="'Welcome, ' + account.name" class="mt-3">
        <b-card-text>
          <div>
            Account: <code>{{ account.id }}</code>
          </div>
          <div>
            Balance:
            <code
            >{{ account.currency === "usd" ? "$" : "€"
              }}{{ account.balance }}</code
            >
          </div>
        </b-card-text>
        <b-button size="sm" variant="success" @click="show = !show"
        >New payment
        </b-button
        >

        <b-button
          class="float-right"
          variant="danger"
          size="sm"
          nuxt-link
          to="/"
        >Logout
        </b-button
        >
      </b-card>

      <b-card class="mt-3" header="New Payment" v-show="show">
        <b-form @submit.prevent="makeTransaction">
          <b-form-group id="input-group-1" label="To:" label-for="input-1">
            <b-form-input
              id="input-1"
              size="sm"
              v-model="payment.to"
              type="number"
              required
              placeholder="Destination ID"
            ></b-form-input>
          </b-form-group>

          <b-form-group id="input-group-2" label="Amount:" label-for="input-2">
            <b-input-group prepend="$" size="sm">
              <b-form-input
                id="input-2"
                v-model="payment.amount"
                type="number"
                required
                placeholder="Amount"
              ></b-form-input>
            </b-input-group>
          </b-form-group>

          <b-form-group id="input-group-3" label="Details:" label-for="input-3">
            <b-form-input
              id="input-3"
              size="sm"
              v-model="payment.details"
              required
              placeholder="Payment details"
            ></b-form-input>
          </b-form-group>

          <b-button type="submit" size="sm" variant="primary">Submit</b-button>
        </b-form>
      </b-card>

      <b-card class="mt-3" header="Payment History">
        <b-table striped hover :items="transactions"></b-table>
      </b-card>
    </div>
  </div>
</template>

<script lang="ts">
  import axios from "axios";

  export default {
    data() {
      return {
        show: false,
        payment: {},
        account: null,
        transactions: null,
        loading: true
      };
    },

    async asyncData(ctx: any) {
      const account = await axios({
        method: 'get',
        url: `http://localhost:5000/api/accounts/${ctx.route.params.id}`
      }).then((response) => {
        return response.data.item;
      });

      const transactions = await axios({
        method: 'get',
        url: `http://localhost:5000/api/accounts/${ctx.route.params.id}/transactions`
      }).then((response) => {
        return response.data.items;
      });

      return {
        account,
        transactions,
        loading: false,
        show: true
      }
    },

    methods: {
      makeTransaction() {
        const from = (this as any).account.id;
        const to = (this as any).payment.to;
        const details = (this as any).payment.details;
        const amount = (this as any).payment.amount;
        const data = new FormData();

        data.append('from', from);
        data.append('to', to);
        data.append('details', details);
        data.append('amount', amount);

        return axios({
          method: 'post',
          url: `http://localhost:5000/api/transactions`,
          data: data,
          withCredentials: false
        }).then((response) => {
          if (response.data.errors.length) {
            alert(response.data.errors[0]);
          }
          window.location.reload();
        });
      }
    }
  };
</script>
