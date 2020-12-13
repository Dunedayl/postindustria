1) select all users first/last name who made a purchase of products from "Office" category in "Rite Aid" shop for last 10 years

select
  firstname,
  lastname
from
  users
  join orders on userId = users.id
  join shops on orders.shopId = shops.id
  join shopcategory on shops.id = shopcategory.shopId
  join categories on categories.id = shopcategory.categoryId
where
	shops.name = "Rite Aid" and categories.name = "Office" and orders.order_date >= DATE_ADD(CURRENT_DATE(), INTERVAL -10 YEAR) 

2) select names of all categories and count the number of purchases of products from that category

select
  categories.name,
  count (productorder.id)
from
  orders
  join productorder on productorder.orderId = orders.id
  join products on products.id = productorder.productId
  join productcategory on productcategory.productId = products.id
  join categories on categories.id = productcategory.categoryId
group by
  categories.name

3) select users first/last name who have more then one purchase in "Kroger" shop

select
  firstname,
  lastname
from
  orders
  join users on users.id = orders.userId
  join shops on shops.id = orders.shopId
where
  shops.name = "Kroger"
group by
  userId
having
  (count (orders.id) > 1)

4) show profit amount per month by particular shop (Might be useful in reporting)

select
  sum(summa),
  number_month,
  number_year
from
  (
    select
      orders.summa,
      MONTH(orders.order_date) as number_month,
      YEAR(orders.order_date) as number_year
    from
      orders
      join shops on shops.id = orders.shopId
    where
      shops.name = "Kroger"
  ) mothProf
group by
  number_month,
  number_year

5) search a user by its full name oк part of it

select
  *
from
  users
where
  firstName like "%Alfreda Kli%"
  or lastName like "%Alfreda Kli%"
  or concat(firstName, " ", lastName) like "%Alfreda Kli%"


6) show amount of all purchases made by a user

select
  sum (orders.summa)
from
  orders
  join users ON users.id = orders.userId
where
  users.id = "1999"

7) select users first/last name who have purchases only at "Kroger" shop

select
  users.firstName,
  users.lastName,
  users.id,
  sum(
    case
      when shops.name = "Kroger" then 1
      else 0
    end
  ) as kroger,
  sum(
    case
      when shops.name <> "Kroger" then 1
      else 0
    end
  ) as another
from
  orders
  join users on users.id = orders.userId
  join shops on shops.id = orders.shopId
group by
  users.firstName,
  users.lastName,
  users.id
having
  another = 0
  and kroger >= 1;

8) select users first/last name who have purchases purchases in all shops
(обратите внимание, всех магазинов, то есть запрос должен продолжать правильно работать даже если
я вручную добавлю в базу еще магазинов и покупок)

select
  firstName,
  lastName
from
  (
    select
      users.id
    from
      (
        select
          DISTINCT orders.userId,
          orders.shopId
        from
          orders
      ) as ds
      join users on users.id = userId
    where
      userId
    group by
      users.id
    having
      count(Distinct shopId) = (
        select
          count(Distinct id)
        from
          shops
      )
  ) as da
  join users on users.id = da.id