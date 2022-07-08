const CurrentOrder = {
    template: `
    <div v-if="data" class="m-auto text-center" style="border: 5px solid deepskyblue; width: 500px; padding: 15px">
        <h1>{{data.message}}</h1>
        <div>
            <h5>Пицца: {{data.currentOrder.pizza_title}}</h5>
            <h5>Размер пиццы: {{data.currentOrder.pizza_size}} см</h5>
            <h3>Стоимость пиццы: {{data.currentOrder.pizza_cost_BYN}} руб ({{data.currentOrder.pizza_cost}}$)</h3>
        </div>
        <div v-if="data.currentOrder.sauce_title">
            <h5>Соус: {{data.currentOrder.sauce_title}}</h5>
            <h3>Стоимость соуса: {{data.currentOrder.sauce_cost_BYN}} руб ({{data.currentOrder.sauce_cost}}$)</h3>
        </div>
        <h4 class="text-danger">Дата заказа: {{data.currentOrder.order_date}}</h4>
        <h3>Итого: {{data.currentOrder.total_cost_BYN}} руб ({{data.currentOrder.total_cost}}$)</h3>
        <div>
            <h5 class="text-success">Спасибо за покупку!</h5>
            <router-link class="btn btn-success" to="/">Продолжить</router-link>
        </div>
    </div>
    `,
    mounted() {
        const order = JSON.parse(sessionStorage.getItem('currentOrder'))
        if (!order) {
            router.push('/')
        }
        this.data = order
    },
    data() {
        return {
            data: null
        };
    }
}