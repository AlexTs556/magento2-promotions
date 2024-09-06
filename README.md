# Magento 2 Promotions Module

## Overview

The **Promotions** module for Magento 2 provides functionality to manage promotions and their associated groups. This module allows you to interact with promotions and promotion groups via a REST API, offering operations for listing, adding, and deleting data. The API responses are in JSON format, and it integrates with Magento's default Swagger for API documentation and testing.

## Features

- **Manage Promotions**: Create, list, and delete promotions.
- **Manage Promotion Groups**: Create, list, and delete promotion groups.
- **Associations**: Link multiple promotions to multiple groups.
- **REST API**: Access and manage promotions and promotion groups through RESTful endpoints.
- **Swagger Integration**: Test and interact with the API via Magento's default Swagger documentation.

## Installation

### 1. Install via `app/code` Directory

1. Clone the repository:

    ```bash
    git clone https://github.com/AlexTs556/magento2-promotions.git
    ```

2. Copy the module to your Magento installation:

    ```bash
    cp -R magento2-promotions/ <Magento_Root>/app/code/Kodano/Promotions/
    ```

3. Enable the module:

    ```bash
    php bin/magento setup:upgrade
    php bin/magento setup:di:compile
    ```

### 2. Install via Composer

1. Add the repository to your `composer.json`:

    ```bash
    composer require kodano/module-promotions
    ```

2. Enable the module:

    ```bash
    php bin/magento setup:upgrade
    php bin/magento setup:di:compile
    ```

## Usage

Once installed, you can access the promotions API through Magento's Swagger UI. The API endpoints available are:

- **List Promotions**: `GET /V1/promotions`
- **Add Promotion**: `POST /V1/promotions`
- **Delete Promotion**: `DELETE /V1/promotions/:promotionId`
- **List Promotion Groups**: `GET /V1/promotion-groups`
- **Add Promotion Group**: `POST /V1/promotion-groups`
- **Delete Promotion Group**: `DELETE /V1/promotion-groups/:groupId`

You can test and interact with these endpoints using the Swagger UI, accessible at `/swagger` in your Magento admin panel.

## API Documentation

API documentation is available through Magento's default Swagger interface. Navigate to `/swagger` in your Magento admin panel to view and test the available endpoints.

## Support

If you encounter any issues or need support, please open an issue on the [GitHub repository](https://github.com/AlexTs556/magento2-promotions/issues).
