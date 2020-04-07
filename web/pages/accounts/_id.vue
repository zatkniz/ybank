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
              >{{ account.currency.sign}}{{ account.balance }}</code
            >
          </div>
        </b-card-text>
        <b-button size="sm" variant="success" @click="show = !show"
          >New payment</b-button
        >

        <b-button
          class="float-right"
          variant="danger"
          size="sm"
          nuxt-link
          to="/"
          >Logout</b-button
        >
      </b-card>

      <b-card class="mt-3" header="New Payment" v-show="show">
        <b-form @submit.prevent="onSubmit">
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

          <b-form-group id="input-group-currency" label="Currency:" label-for="input-currency">
              <b-form-select 
                id="input-currency" 
                value-field="id" 
                text-field="name" 
                required 
                v-model="payment.currency_id" 
                :options="currencies" 
                size="sm">
              </b-form-select>
          </b-form-group>

          <b-form-group id="input-group-2" label="Amount:" label-for="input-2">
            <b-input-group :prepend="getCurrencySign()" size="sm">
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
        <b-table striped hover :items="transactions" :fields="fields"></b-table>
      </b-card>
    </div>
  </div>
</template>

<script>
import axios from "axios";
import Vue from "vue";

export default {
  data() {
    return {
      show: false,
      payment: {
        currency_id: 1
      },
      fields: [
        { key: 'id', label: 'Transaction Id' },
        { key: 'sender.name', label: 'Sender Name' },
        { key: 'receiver.name', label: 'Receiver Name' },
        { key: 'amount', label: 'Amount' },
        'details',
      ],
      account: null,
      transactions: [],
      currencies: [],
      loading: true
    };
  },

  async mounted() {
    try {
      await this.getAccount();
      await this.getAccountTransactionsHistory();
      await this.getCurrencies();
    } catch (error) {
      console.log(error);
    }
  },

  methods: {
    async getAccount() {
      try {
        const { data } = await this.$axios.get(`/accounts/${this.$route.params.id}`);
        this.account = data;
        this.loading = false;
      } catch (error) {
        console.log(error);
      }
    },
    getCurrencySign() {
        const currency = this.currencies.find( currency => currency.id == this.payment.currency_id);
        return currency?.sign;
    },
    async getCurrencies() {
      try {
        const { data } = await this.$axios.get(`/currencies`);
        this.currencies = data;
      } catch (error) {
        console.log(error);
      }
    },
    async getAccountTransactionsHistory() {
      try {
        const { data } = await this.$axios.get(`/accounts/${this.$route.params.id}/transactions`);
        this.transactions = data;
      } catch (error) {
        console.log(error);
      }
    },
    async onSubmit() {
        this.payment.from = this.$route.params.id;

        await this.$axios.post(`/transactions`,this.payment).then(async res => {
          this.$bvToast.toast(`Payment Succesfully Sent`, {
            title: 'Success',
            autoHideDelay: 3000,
            appendToast: false,
            variant: 'success'
          })

          await this.getAccount();
          await this.getAccountTransactionsHistory();

          this.payment = {
            currency_id: 1
          };
          this.show = false;
        }).catch(error => {
          this.$bvToast.toast(error.response.data.message, {
            title: 'Error',
            autoHideDelay: 3000,
            appendToast: false,
            variant: 'danger'
          })
        });
    }
  }
};
</script>
