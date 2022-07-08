const OrderPizza = {
    template: `
    <div class="container">
    <h1 class="text-center">Заказать пиццу</h1>
    <div v-if="dataIsFetching" class="text-center">
      <div class="spinner-border" role="status"></div>
    </div>
    <div v-else-if="error" class="text-center">
      <h2 class="text-center text-danger">{{error}}</h2>
    </div>
    <div v-else class="row">
    <div class="col-4">
    <div>
        <div v-if="currentStep >= 1">
            <label>
            <h3>1. Выберите пиццу</h3>
                <select v-model="currentPizzaId" @change="changeStep(2)" class="form-control">
                    <template v-for="pizza in pizzas">
                        <option :value="pizza.id">{{pizza.title}}</option>
                    </template>
                </select>
            </label>
        </div>
        <div v-if="currentStep >= 2">
            <label>
            <h3>2. Выберите размер (см)</h3>
                <select v-model="currentSizeId" @change="changeStep(3)" class="form-control" >
                    <template v-for="pizzaSize in pizzaSizes">
                        <option :value="pizzaSize.id">{{pizzaSize.size}}</option>
                    </template>
                </select>
            </label>
        </div>
        <div v-if="currentStep >= 3">
            <label>
            <h3>3. Выберите соус</h3>
                <select v-model="currentSauceId" class="form-control">
                    <option :value="null">Без соуса</option>
                    <template v-for="pizzaSauce in pizzaSauces">
                        <option :value="pizzaSauce.id">{{pizzaSauce.title}}</option>
                    </template>
                </select>
            </label>
        </div>
    </div>
</div>
<div class="col-4">
    <div v-if="currentPizza">
        <h3 class="text-center">{{this.currentPizza.title}}</h3>
        <img class="rounded mx-auto d-block" style="width: 350px" :src="this.currentPizza.image" alt="this.currentPizza.title">
        <p class="text-center">{{this.currentPizza.text}}</p>
    </div>
    <div v-if="currentSauce">
        <h3 class="text-center">{{this.currentSauce.title}} соус</h3>
        <p class="text-center">{{this.currentSauce.text}}</p>
    </div>
</div>
<div v-if="pizzaCost" class="col-4" style="border: 1px solid orangered">
    <h3 class="text-center">Корзина</h3>
    <div v-if="pizzaCost">
        <h6 class="text-center">Пицца - {{this.currentPizza.title}}</h6>
        <h6 class="text-center">Размер - {{this.currentSize.size}} см</h6>
        <h5 class="text-center">Стоимость пиццы: {{pizzaCost.cost_BYN}} руб. ({{pizzaCost.cost_USD}}$)</h5>
    </div>
    <div v-if="currentSauce">
        <h6 class="text-center">Соус - {{this.currentSauce.title}}</h6>
        <h5 class="text-center">Стоимость соуса: {{this.currentSauce.cost_BYN}} руб. ({{this.currentSauce.cost}}$)</h5>
    </div>
    <h4 class="text-center text-danger">Итого: {{this.totalCost.cost_BYN}} руб. ({{this.totalCost.cost_USD}})$</h4>
    <div class="text-center" >
        <button @click="createOrder" type="button" class="btn btn-success m-auto">Заказать</button>
    </div>
</div>
</div>
</div>`,
    data() {
        return {
            dataIsFetching: false,
            error: null,
            currentStep: 1,
            pizzas: [],
            pizzaSizes: [],
            pizzaSauces: [],
            currentPizzaId: null,
            currentSizeId: null,
            currentSauceId: null
        }
    },
    mounted() {
        this.fetchData()
    },
    methods: {
        changeStep(step) {
            if (step > this.currentStep) {
                this.currentStep = step
            }
        },
        fetchData() {
            this.error = null
            this.dataIsFetching = true
            this.getPizzas()
            this.getPizzaSizes()
            this.getPizzaSauces()
            this.dataIsFetching = false
        },
        async getPizzas() {
            try {
                const {data} = await axios.get('/api/getPizza.php')
                this.pizzas = data
            } catch (e) {
                this.error = 'Произошла ошибка! Попробуйте позже.'
            }
        },
        async getPizzaSizes() {
            try {
                const {data} = await axios.get('/api/getPizzaSizes.php')
                this.pizzaSizes = data
            } catch (e) {
                this.error = 'Произошла ошибка! Попробуйте позже.'
            }
        },
        async getPizzaSauces() {
            try {
                const {data} = await axios.get('/api/getPizzaSauces.php')
                this.pizzaSauces = data
            } catch (e) {
                this.error = 'Произошла ошибка! Попробуйте позже.'
            }
        },
        async createOrder() {
            const orderData = {
                pizza_title: this.currentPizza.title,
                pizza_size: this.currentSize.size,
                pizza_cost: this.pizzaCost.cost_USD,
                pizza_cost_BYN: this.pizzaCost.cost_BYN || null,
                sauce_title: this.currentSauce ? this.currentSauce.title : null,
                sauce_cost: this.currentSauce ? this.currentSauce.cost : null,
                sauce_cost_BYN: this.currentSauce ? this.currentSauce.cost_BYN : null,
                total_cost: this.totalCost.cost_USD,
                total_cost_BYN: this.totalCost.cost_BYN || null,
            }
            const {data} = await axios.post('/api/createOrder.php', orderData)
            console.log(data)
        }
    },
    computed: {
        currentPizza() {
            return this.pizzas.find(pizza => pizza.id === this.currentPizzaId)
        },
        currentSize() {
            return this.pizzaSizes.find(size => size.id === this.currentSizeId)
        },
        currentSauce() {
            return this.pizzaSauces.find(sauce => sauce.id === this.currentSauceId)
        },
        pizzaCost() {
            if (this.currentPizza && this.currentSize) {
                return {
                    cost_USD: (this.currentPizza.cost * this.currentSize.coefficient).toFixed(2),
                    cost_BYN: (this.currentPizza.cost_BYN * this.currentSize.coefficient).toFixed(2),
                }
            }
        },
        totalCost() {
            if (this.currentSauce) {
                return {
                    cost_USD: (Number(this.pizzaCost.cost_USD) + this.currentSauce.cost).toFixed(2),
                    cost_BYN: (Number(this.pizzaCost.cost_BYN) + this.currentSauce.cost_BYN).toFixed(2),
                }
            }
            return this.pizzaCost
        }
    }
}