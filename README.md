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

## Endpoint Security
With enough time, I would implement an oauth provider service and a proper oauth client in my unit tests.
For now, there is only one key which is defined in the .env. The key must be provided to operate restricted
actions by including it as a header named `auth-key` or a form parameter named `_auth_key`.
You can look at the unit tests for example payloads.

## Prerequsites

We use [Docker](https://www.docker.com/products/docker) to administer this test. This ensures that we get an identical result to you when we test your application out, and it also matches our internal development workflows. If you don't have it already, you'll need Docker installed on your machine. **The application MUST run in the Docker containers** - if it doesn't we cannot accept your submission. You **MAY** edit the containers or add additional ones if you like, but this **MUST** be clearly documented.

We have provided some containers to help build your application in either PHP, Go or Python, with a variety of persistence layers available to use.

### Tech

- Valid PHP 7.1, Go 1.8, or Python 3.6 code
- Persist data to either Postgres, Redis, or MongoDB (in the provided containers).
    - Postgres connection details:
        - host: `postgres`
        - port: `5432`
        - dbname: `hellofresh`
        - username: `hellofresh`
        - password: `hellofresh`
    - Redis connection details:
        - host: `redis`
        - port: `6379`
    - MongoDB connection details:
        - host: `mongodb`
        - port: `27017`
- Use the provided `docker-compose.yml` file in the root of this repository. You are free to add more containers to this if you like.

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
