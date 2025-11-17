import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../wayfinder'
/**
* @see \App\Http\Controllers\Api\TransactionController::index
* @see app/Http/Controllers/Api/TransactionController.php:21
* @route '/api/v1/transactions'
*/
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/api/v1/transactions',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Api\TransactionController::index
* @see app/Http/Controllers/Api/TransactionController.php:21
* @route '/api/v1/transactions'
*/
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Api\TransactionController::index
* @see app/Http/Controllers/Api/TransactionController.php:21
* @route '/api/v1/transactions'
*/
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Api\TransactionController::index
* @see app/Http/Controllers/Api/TransactionController.php:21
* @route '/api/v1/transactions'
*/
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Api\TransactionController::index
* @see app/Http/Controllers/Api/TransactionController.php:21
* @route '/api/v1/transactions'
*/
const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Api\TransactionController::index
* @see app/Http/Controllers/Api/TransactionController.php:21
* @route '/api/v1/transactions'
*/
indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Api\TransactionController::index
* @see app/Http/Controllers/Api/TransactionController.php:21
* @route '/api/v1/transactions'
*/
indexForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

index.form = indexForm

/**
* @see \App\Http\Controllers\Api\TransactionController::store
* @see app/Http/Controllers/Api/TransactionController.php:37
* @route '/api/v1/transactions'
*/
export const store = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/api/v1/transactions',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Api\TransactionController::store
* @see app/Http/Controllers/Api/TransactionController.php:37
* @route '/api/v1/transactions'
*/
store.url = (options?: RouteQueryOptions) => {
    return store.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Api\TransactionController::store
* @see app/Http/Controllers/Api/TransactionController.php:37
* @route '/api/v1/transactions'
*/
store.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Api\TransactionController::store
* @see app/Http/Controllers/Api/TransactionController.php:37
* @route '/api/v1/transactions'
*/
const storeForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: store.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Api\TransactionController::store
* @see app/Http/Controllers/Api/TransactionController.php:37
* @route '/api/v1/transactions'
*/
storeForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: store.url(options),
    method: 'post',
})

store.form = storeForm

const TransactionController = { index, store }

export default TransactionController