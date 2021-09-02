## Description
Simple invoices system which helps in factoring process the project includes:
1. Invoices
2. Creditors
3. Debtors
4. InvoiceItems (next step)

The project allows users to:
1. Creditors
    * Index with pagination creditors.
    * Create new creditors.
    * Update existing creditors.

2. Debtor
    * Index with pagination debtors.
    * Create new debtors.
    * Update existing debtors.

3. Invoices
    * Index with pagination invoices.
    * Index by `deptor_id` with pagination invoices.
    * Index by `creditor_id`with pagination invoices.
    * Create new invoices.
    * Update existing invoices.
    * Mark invoices as open.
    * Mark invoices as paid.
    * Delete existing invoices.

## Database structure
1. Creditors: 

    includes `name`, `email`, `phone` normal string fields to store creditor info and `contact_info` which is a JSON field to store any additional data.

2. Debtors: 

    includes `name`, `email`, `phone` normal string fields to store creditor info, `contact_info` which is a JSON field to store any additional data and `total_debt` which is a decimal field to store total debt which should never exceed the total debt limit.

3. Currencies: 

    includes `name`, `code` to store allowed currencies (Would be useful in next steps for now only USD allowed).

4. Invoices: 

    * `total_amount`: decimal field to store invoice amount.
    * `debtor_id`: foreign key stored the debtor id.
    * `creditor_id`: foreign key stored the creditor id.
    * `currency_id`: foreign key stored the currency id.
    * `due_date`: date field to store the invoice due date.
    * `open_date`: nullable DateTime fields the system fill them when status updated.
    * `paid_date`: nullable DateTime fields the system fill them when status updated.
    * `status`: enum field with three possible cases [`initialize`, `open`, `paid`] and `initialize` is the default state of any invoice created in this status.

## Scenarios
* The user creates a new creditor, debtor and try to add a new invoice with amount `30.50` which is added successfully to the system with status `initialize`:
    * Scenario 1
        * User mark the invoice as `open` the system will updates the debtor `total_debt` to `30.50` if a user marks the invoice as `paid` system will update the `total_debt` again and the `total_debt` will be `0.00`.

    * Scenario 2
        * User keep adding and invoices without opening anyone, in this case, the system will just keep accepting new invoices but when he tries to open any of the users will only be able to open invoices until the `total_debt` = `TotalDebtLimit` after that user will be no longer able to add new or mark invoices as paid.
    
    * Scenario 3
        * User updates the invoice and when he tries t open it the system will check the new amount to total debt (can only be performed with `initialize` invoices).
    
    * Scenario 4
        * User deletes the invoice which will not affect any part of the process cause the amount didn't add to total debt till the invoice is open (can only be performed with `initialize` invoices).
    
## Notes

* The default status of any created invoice is `initialize` in this status user can update or delete the invoice, in this status `total_amount` didn't add to the debtor `total_debt` yet.

* User can't update or delete any invoice marked as `open` or `paid`.

* When a user tries to create a new invoice or mark the invoice as paid the invoice amount checked the `total_dept` and the `TotalDebtLimit` ( Which equals 20000) in the system. 

* Creating a new invoice while debtor exceeds the limit isn't allowed but updating an `initialize` invoice total amount is allowed (cause it's not open yet which means that the total amount didn't add to the debtor total debit yet), however not all attributes can be updated like `debtor_id` since total amount didn't add to total debt yet so if the user needs to remove initialize invoice it's possible.

* When invoice marked as open an `InvoiceMarkedAsOpenEvent` is fired in the background to add the invoice `total_amount` to debtor `total_debt`.

* When invoice marked as paid an `InvoiceMarkedAsPaidEvent` is fired in the background to subtract the invoice `total_amount` to debtor `total_debt`.

* User can add, update and delete any `initialize` invoice but after this status, the only possible action to do is to mark it as paid.

* The key here is the total amount of open invoices.

## App Structure 

* `ValidationForms` handles the basic validation.
* `Controllers` acts as a gate it calls the services and returns the response to the user in JSON format.
* `Services` handles the business layer.
* `Validators` is an extra layer of validation to handle logical validation like prevent adding a new invoice if the `TotalDebtLimit` exceeded.
* `Repostitories` handles the database layer.
* `Events And Listeners` is an implementation to observer design pattern where the service can process jobs in the background (in the queue) and return the response to the user without waiting (asynchronous).

## How to run

### Local

1. Clone the project.
2. Run `composer install`.
3. Create a database.
4. Set up .env (set QUEUE_CONNECTION=database to run the queue async).
5. Run `php artisan migrate`
6. Run `php artisan db:seed` which will create face data and seed it in the database if you want an empty database Run `php artisan db:seed --class=CurrencyTableSeeder` which will only seed the currencies (must be run to add USD to currencies).
7. Run `vendor/bin/paratest -p4 --runner=WrapperRunner` which will run the tests in parallel or `vendor/bin/phpunit` to use PhpUnit.
8. Run `php artisan queue:listen` to run the queued process in background like `InvoiceMarkedAsOpenEvent`.
9. Run `php artisan serv` to start the local server.
10. Visit 'http://localhost:8000/api/'

Note: you can set `QUEUE_CONNECTION=sync` in the .env file which will run the queue in synchronous mode and in this case step 9 will not be needed. 

## API's
For API collection import `Invoices.postman_collection.json` you will find it in project root.

## What next
To check what the next steps should be check `next_steps.txt` you will find it in project root.