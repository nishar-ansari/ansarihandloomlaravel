# Prompt: Redesign Product Management Module

I have already developed my eCommerce website for **Ansari Handloom** using **Laravel, MySQL, HTML, CSS, Bootstrap/Tailwind, JavaScript**, and Flutter apps consume data through Laravel REST APIs.

The website is mostly complete, but I am not satisfied with my current **Category, Product, SKU, and Attribute** design. I want to redesign only this module without affecting the rest of the application.

Please act as a **Senior ERP and eCommerce Solution Architect**.

Your goal is to redesign this module using enterprise best practices similar to Shopify, Magento, WooCommerce, and ERPNext.

## Business

I sell:

* Sarees
* Suits
* Dress Materials
* Dupattas
* Lehengas

More categories may be added in the future.

## Requirements

Design a scalable relationship between:

**Category → Product → SKU**

where:

* Category groups products.
* Product represents one design/style.
* SKU represents one sellable variation.

## Dynamic Attributes

Do **not** use fixed database columns like `color`, `border`, or `pattern`.

Instead, create a fully dynamic attribute system.

Examples:

**Saree**

* Colour
* Border
* Pattern
* Fabric
* Blouse Available

**Suit**

* Colour
* Top Length
* Bottom Length
* Dupatta Included
* Fabric

**Lehenga**

* Colour
* Work Type
* Blouse Included

The admin should decide which attributes belong to each category.

## Images

Explain the best way to manage:

* Product images
* SKU-specific images
* Multiple images
* Colour-specific images
* Default image

## Pricing & Inventory

Explain where the following should be stored:

* MRP
* Selling Price
* Cost Price
* Stock
* Low Stock Alert
* Barcode
* Weight
* Dimensions

## Database Design

Design a normalized MySQL database including:

* categories
* products
* skus
* attributes
* attribute_values
* category_attributes
* sku_attribute_values
* product_images
* sku_images

Show all relationships, primary keys, foreign keys, and important indexes.

## Admin Workflow

Explain the complete workflow:

1. Create Category
2. Assign Attributes to Category
3. Create Product
4. Generate/Create SKUs
5. Upload Images
6. Set Price
7. Update Stock
8. Publish Product

## Customer Workflow

Explain how customers browse products, select variants, view colour-specific images, see updated prices, and add the selected SKU to the cart.

## Business Rules

Clearly explain:

* What belongs to Category
* What belongs to Product
* What belongs to SKU
* What belongs to Attribute
* What belongs to Attribute Value

Also explain **why** each piece of data belongs there.

Finally, recommend the best architecture for a Laravel + MySQL + Flutter eCommerce application that can scale to thousands of products and millions of SKU records.
