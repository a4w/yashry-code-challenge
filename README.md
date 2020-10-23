## Problem and solution
This is a challenge asking to design and implement a simple system that calculates some information about a cart like subtotal, taxes, discount, etc... given the products inside and the currency required in the output.
In this document I'm proposing a design solution and this repository has the implementation of the discussed design.
This solution focuses only on **back-end** implementation and provides output as JSON, accessible from a very simple Restful endpoint.

## How to run
This can be run using PHP built in development, However first dependencies must be installed.
Make sure you have [composer](https://getcomposer.org/download/) installed before running the following commands using a terminal in the repository root directory.

 1. `composer install` 
 2. `php -S localhost:8000 src/Presentation/Http/index.php`

This will run the development server, now we need to send a HTTP request to the server, This can be done by:
1. Postman 
2. cURL

`curl --location --request POST 'localhost:8000/cart' --header 'Content-Type: application/json' --data-raw '{"products":["T-shirt", "T-shirt", "Shoes", "Jacket"], "currency": "USD"}'`


Outputs: 


    {
    	"subtotal":"$66.96",
    	"taxes":"$9.3744",
    	"offers":
    		[
    			{
    				"value":"-$2.499",
    				"offer_name":"10% off shoes"
    			},
    			{
    				"value":"-$9.995",
    				"offer_name":"50% off jacket"
    			}
    		],
        "total":"$63.8404"
    }

## Architecture
I'm using a layered architecture, where each layer strictly depends on layer below it only with the exception of the infrastructure layer; All layers have a dependency on the infrastructure layer services (to provide Logging for example) but all the dependencies are upon interfaces to allow substituting implementations of the infrastructure without touching neither Application nor the domain layer.
The *domain layer* has no dependencies (except infrastructure interfaces) to aid maintainability.
In the following diagram the arrows represents dependencies between layers.

![Architecture diagram](/arch.png)

The *infrastructure layer* is used to provide implementations of contracts that are themselves not part of the domain i.e Persistence implementation and Logging. That's why the infrastructure is dependent on the *domain layer*, specifically the domain entities.

The reason I'm using this architecture is because it allows extending the system very easily due to no deep layers dependencies (The problem statement required strictly following the Open-Closed principle, that's why I used an architecture that is easily extendible).

Please note that the only file that is not following this architecture is `bootstrap.php` as I chose not to use any framework nor config managers (They seemed like an overkill and out of scope for this demonstration). Inside I bootstrap the application for the testcase i.e Auto-load classes, Setup Dependency injection container, Hydrate repositories (seeding) with test data and handle HTTP request life-cycle (routing).

## What I'm not happy about (could be improved with more time)
1. Using plain old `Exception` class for exceptions, Usually I would create business specific exceptions.
2. I tried to make Unit tests to cover as much important details as possible, but it's not 100% coverage (no unit tests for in memory repositories for example). Also I would prefer to use an object builder instead of object mother to keep tests simpler and more DRY.
3. There is no framework (full or in parts) (no configuration, no routing, etc...)
4. There is no logging/monitoring. This is why from implementation side there seems to be no dependency from the *Domain layer* to the *Infrastructure* as there are no Infrastructure services.
5. The use of a DTO assembler could have help decouple the DTO completely from the domain objects.

## Use-case (RestAPI)
1. The client sends a `POST` HTTP request with JSON body to the endpoint `/cart` with fields `products:array` and `currency:string`.
	1. If the JSON is malformed and error is raised and use-case ends.
	2. If the JSON doesn't contain an array of product names and error is raised and use-case ends.
	3. If any of the supplied product names are not found, an error is raised and use-case ends.
	4. If the supplied currency code is malformed or not found, an error is raised and use-case ends.
2. The system responds with the cart information as JSON in the response body.

### Data Flow (RestAPI example)

![Data diagram](/data.png)

## Implementation details

#### File structure

    src
    ├── Application
    │   ├── DataTransferObject
    │   │   ├── CreateCartFromProductResponse.php
    │   │   ├── CreateCartFromProductsRequest.php
    │   │   └── OfferResponse.php
    │   └── Service
    │       ├── CreateCartFromProducts.php
    │       └── ICreateCartFromProducts.php
    ├── Domain
    │   ├── Cart
    │   │   ├── CartFactory.php
    │   │   ├── CartItem.php
    │   │   └── Cart.php
    │   ├── Offer
    │   │   ├── Offer.php
    │   │   └── Service
    │   │       ├── BuyXProductGetYPercentOffProduct.php
    │   │       ├── IOfferSpecification.php
    │   │       ├── IOfferSpecificationRepository.php
    │   │       └── ProductDiscountedPercent.php
    │   ├── Product
    │   │   ├── Product.php
    │   │   └── Service
    │   │       ├── IProductRepository.php
    │   │       ├── ITaxCalculator.php
    │   │       └── RegularTaxCalculator.php
    │   └── ValueObject
    │       ├── Currency.php
    │       ├── MoneyFactory.php
    │       └── Money.php
    ├── Infrastructre
    │   └── Persistence
    │       ├── InMemoryOfferSpecificationRepository.php
    │       └── InMemoryProductRepository.php
    └── Presentation
        ├── DataTransformers
        │   ├── DefaultTransformer.php
        │   └── IJsonTransformer.php
        └── Http
            ├── CartController.php
            └── index.php
    
    15 directories, 26 files

1. `Application` is where all the *Application layer* lives. It consists of two sub-folders:
	1. `DataTransferObject` is where all the DTO definitions live. They are used to prevent leaking the *Domain layer* to the presentation layer by encapsulating data.
	2. `Services` is where all the application services live. They are used to coordinate *Domain layer* services and act as a system boundary agnostic to the system client.
2. `Domain` is where the *Domain layer* lives. It consists of all the domain objects including `Entities`, `ValueObjects`, `Aggregates` and `Domain Services` such as `Repositories` and `Factories`. The domain is the main concern of any business (at least in `Domain-Driven-Design`) that's why it has no dependencies and all the behaviors are pushed toward this layer.
3. `Infrastructre` is where all system infrastructure and technical details live. It should offer `Infrastructure services` as well as implementation for `Domain service interfaces` that are not part of the domain itself.
4. `Presentation` is the top-most layer. No other layers should depend on this layer as a matter of separation of concerns; This allows to easily add more ways for the user to access the system (Adding a command line interface or HTML form is just a matter of creating their controller and views (or in any architecture required) using existing exposed DTO from the *Application layer*. It currently has two sub-folders:
	1. `DataTransformers` which are responsible for converting DTOs received from the *Application layer* into a form consumable by the client.
	2. `Http` is where the HTTP rest controllers lives.

### Persistence
I used in memory implementation for the mocking persistence for simplicity and the lack of a requirement to use a fully fledged database.

