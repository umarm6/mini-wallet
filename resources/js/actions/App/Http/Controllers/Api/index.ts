import AuthController from './AuthController'
import TransactionController from './TransactionController'

const Api = {
    AuthController: Object.assign(AuthController, AuthController),
    TransactionController: Object.assign(TransactionController, TransactionController),
}

export default Api