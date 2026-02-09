<h1>Biztonsagi ertesites</h1>
<p>Ervenytelen adatbazis muvelet tortent</p>
<ul>
    <li><strong>Üzenet:</strong>{{ $logData['message']}}</li>
    <li><strong>Felhasználó:</strong>{{ $logData['user']}}</li>
    <li><strong>Művelet:</strong>{{ $logData['action']}}</li>
    <li><strong>Url:</strong>{{ $logData['url']}}</li>
    <li><strong>IP:</strong>{{ $logData['ip']}}</li>
</ul>

@if( isset( $logData['payload'] ) )
    <h3>Küldött adatok:</h3><br>
    <pre>{{ $logData['data'] }}</pre>
@endif