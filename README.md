#Simple Yahoo Weather utility for the command line.

Weather for your zip code, on the command line.  Works great with GeekTool.

    Usage:
    $ php yahoo_weather.php [-cfCF] [zip]

    Flags:
        -c, --conditions          Displays only the current conditions
        -f, --forecast            Displays only the forecast
        -C                        Use Celsius for temperatures
        -F                        Use Farenheit for temperatures (default)

    Example:
    $ php yahoo_weather.php 11201
    > Cloudy, 70˚F
      Wed: Showers Early, H 74˚ | L 63˚
      Thu: Partly Cloudy, H 75˚ | L 61˚
