from datetime import date

year = 1970
current_date = date.today()
current_year = current_date.year

while year <= current_year:
    print(year)
    year += 1
else:
    print('Done')