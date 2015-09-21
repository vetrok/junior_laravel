SELECT Name, GROUP_CONCAT(RawData) AS TestersName
FROM (
  SELECT  Orders.Name AS Name,
  CONCAT(
    GROUP_CONCAT(Testers.LastName, ' ',Testers.FirstName),
    ' (',
    Organizations.Name,
    ')'
  ) AS RawData
  FROM Orders
  INNER JOIN Order_Tester
  ON Orders.Id = Order_Tester.OrderID
  INNER JOIN Testers
  ON Order_Tester.TesterID = Testers.ID
  INNER JOIN Organizations
  ON Testers.OrganizationID = Organizations.ID
  GROUP BY Orders.Name, Organizations.Name
) AS NewTesters
GROUP BY Name
