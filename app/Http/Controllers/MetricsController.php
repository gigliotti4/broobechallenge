<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use App\Models\MetricHistoryRun;
use App\Models\Strategy;
use App\Models\Category;
use Illuminate\Support\Facades\Validator;

class MetricsController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        $strategies = Strategy::all();

        return view('metrics', compact('categories', 'strategies'));
    }

 
    
    public function fetchMetrics(Request $request)
    {
        // Validar los datos del formulario
        $validator = Validator::make($request->all(), [
            'url' => 'required|url',
            'category' => 'required|array',
            'strategy' => 'required|string|in:DESKTOP,MOBILE',
        ]);
    
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 400);
        }
    
        // Obtener datos del formulario
        $url = $request->input('url');
        $categories = $request->input('category');
        $strategy = $request->input('strategy');
    
        // Configurar la URL de la API de Google PageSpeed Insights y la clave de API
        $apiKey = env('GOOGLE_API_KEY');
        $apiUrl = "https://www.googleapis.com/pagespeedonline/v5/runPagespeed";
    
//        // Convertir el array de categorías en una cadena de parámetros category separados por comas
    // $categoryParamsString = implode(',', $categories);

        $client = new Client();

        try {
            $response = $client->request('GET', $apiUrl, [
                'query' => [
                    'url' => $url,
                    'key' => $apiKey,
                    'strategy' => strtoupper($strategy), // Asegúrate de convertir a mayúsculas si es necesario
                    'category' => implode(',', $categories), // Formato adecuado para las categorías
                ],
            ]);
       
            // Procesar la respuesta aquí...
            $data = json_decode($response->getBody()->getContents(), true);
            // Manejar la respuesta de la API según tus necesidades
            
        } catch (RequestException $e) {
            // Captura de errores de solicitud (incluido el 400 Bad Request)
            if ($e->hasResponse()) {
                $errorResponse = json_decode($e->getResponse()->getBody()->getContents(), true);
                // Manejar el error de la API aquí
                return response()->json(['error' => 'Error en la solicitud: ' . $errorResponse['error']['message']], 400);
            } else {
                // Otro tipo de error de solicitud
                return response()->json(['error' => 'Error en la solicitud: ' . $e->getMessage()], 400);
            }
        } catch (\Exception $e) {
            // Otros tipos de excepciones
            return response()->json(['error' => 'Error al procesar la solicitud: ' . $e->getMessage()], 500);
        }
}
            
   

    
    
    public function saveMetricRun(Request $request)
    {
        // Validar datos recibidos del formulario
        $request->validate([
            'url' => 'required|url',
            'accessibility_metric' => 'required|numeric',
            'pwa_metric' => 'required|numeric',
            'performance_metric' => 'required|numeric',
            'seo_metric' => 'required|numeric',
            'best_practices_metric' => 'required|numeric',
            'strategy_id' => 'required|exists:strategies,id',
        ]);

        // Crear una nueva instancia del modelo MetricHistoryRun
        $metricRun = new MetricHistoryRun();
        $metricRun->url = $request->input('url');
        $metricRun->accessibility_metric = $request->input('accessibility_metric');
        $metricRun->pwa_metric = $request->input('pwa_metric');
        $metricRun->performance_metric = $request->input('performance_metric');
        $metricRun->seo_metric = $request->input('seo_metric');
        $metricRun->best_practices_metric = $request->input('best_practices_metric');
        $metricRun->strategy_id = $request->input('strategy_id');

        // Guardar el objeto en la base de datos
        $metricRun->save();

        // Retornar respuesta JSON con mensaje de éxito
        return response()->json(['message' => 'Metric Run saved successfully']);
    }

    public function showHistory()
    {
        // Obtener todos los registros de MetricHistoryRun con relaciones cargadas
        $metricHistory = MetricHistoryRun::with('strategy')->get();

        // Retornar vista con datos
        return view('history', compact('metricHistory'));
    }
}
