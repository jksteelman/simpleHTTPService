This project implements the following:

 * Follows PSR-1, PSR-2, PSR-4, PSR-5, PSR-7
 * Doctrine implemented as a database abstraction layer
 * Custom DB-ORM for create-new-model and update-existing-model using a trait (`$recipe->save()`)
 * isolated environment configurations (.env)
 * composer for dependencies
 * a complete router using PSR-7
 * single point-of-entry bootstrap file for either web traffic or CLI operations
 * web-based setup (obviously, not a solution for "real" projects) for migrations and seeding

## Setup instructions
 1. navigate to the app directory `cd project_directory\app` and run `composer install`
 2. copy the .env.example to .env `cp .env.example .env`
 3. get docker running with `docker-compose up -d` and navigate to `http://localhost`
 4. You should now see three database links for up, down, and seed. Select `migrate up`, then `migrate seed`

Following these instructions, you now have all the required application code, database structure, and optional seeding data


##### Example Endpoints

| Name   | Method      | URL                    | Protected |
| ---    | ---         | ---                    | ---       |
| List   | `GET`       | `/recipes`             | ✘         |
| Create | `POST`      | `/recipes`             | ✓         |
| Get    | `GET`       | `/recipes/{id}`        | ✘         |
| Update | `PUT/PATCH` | `/recipes/{id}`        | ✓         |
| Delete | `DELETE`    | `/recipes/{id}`        | ✓         |
| Rate   | `POST`      | `/recipes/{id}/rating` | ✘         |

An endpoint for recipe search functionality **MUST** also be implemented. The HTTP method and endpoint for this **MUST** be clearly documented.
