<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use App\Models\MetricHistoryRun;
use App\Models\Strategy;
use App\Models\Category;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
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

            // Construir la query manualmente
            $query = [
                'url' => $url,
                'key' => 'key='.$apiKey,
                'strategy' => 'strategy='.$strategy,
            ];

            // Agregar cada categoría individualmente
            foreach ($categories as $category) {
                $query[] = 'category=' . urlencode($category);
            }

            // Construir la URL completa
            $fullUrl = $apiUrl . '?url=' . implode('&', $query);

           
        // dd($fullUrl);

            $client = new Client();

            try {
                $response = $client->request('GET', $fullUrl);

                // Procesar la respuesta aquí...
                $data = json_decode($response->getBody()->getContents(), true);
                // Manejar la respuesta de la API según tus necesidades
            return $data;
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
            try {
                $validatedData = $request->validate([
                    'url' => 'required|url',
                    'strategy' => 'required|string',
                    'accessibility_metric' => 'nullable',
                    'pwa_metric' => 'nullable',
                    'performance_metric' => 'nullable',
                    'seo_metric' => 'nullable',
                    'best_practices_metric' => 'nullable',
                ]);
        
                $metricRun = new MetricHistoryRun();
                $metricRun->url = $validatedData['url'];
                $metricRun->strategy = $validatedData['strategy'];
                $metricRun->accessibility_metric = $validatedData['accessibility_metric'];
                $metricRun->pwa_metric = $validatedData['pwa_metric'];
                $metricRun->performance_metric = $validatedData['performance_metric'];
                $metricRun->seo_metric = $validatedData['seo_metric'];
                $metricRun->best_practices_metric = $validatedData['best_practices_metric'];
                $metricRun->save();
        
               
        
                return response()->json(['message' => 'Métrica guardada correctamente'], 200);
            } catch (\Exception $e) {
                return response()->json(['message' => 'Error al guardar la métrica: ' . $e->getMessage()], 500);
            }
        }




    public function showHistory()
    {
        // Obtener todos los registros de MetricHistoryRun con relaciones cargadas
        $metricHistory = MetricHistoryRun::with('strategy')->get();

        // Retornar vista con datos
        return view('history', compact('metricHistory'));
    }
}
